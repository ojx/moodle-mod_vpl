<?php
// This file is part of VPL for Moodle - http://vpl.dis.ulpgc.es/
//
// VPL for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// VPL for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with VPL for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Manage jail (execution) servers API
 *
 * @package mod_vpl
 * @copyright 2012 Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>
 */

defined('MOODLE_INTERNAL') || die();
require_once( __DIR__ . '/../locallib.php');

/**
 * vpl_jailserver_manager is a utility class to manage
 * the jail servers. get_Server is the main feature
 *
 */
class vpl_jailserver_manager {

    /**
     * Time in seconds to wait before rechecking a server.
     * This is the time to wait before a server is considered down.
     * If set to 0, it will not recheck the server.
     *
     * @var int
     */
    const RECHECK = 300; // Optional setable?

    /**
     * Name of the table jailservers in the database.
     *
     * @var string
     */
    const TABLE = 'vpl_jailservers';

    /**
     * Save last server version.
     *
     * @var string
     */
    private static $lastserverversion = '';

    /**
     * Get the last server version.
     *
     * @return string Last server version
     */
    public static function get_last_server_version() {
        return self::$lastserverversion;
    }

    /**
     * Parse headers from cURL response to get the server version.
     *
     * @param resource $ch cURL handle
     * @param string $header Header string from the response
     * @return int Length of the header string
     */
    public static function parse_headers($ch, $header) {
        $parsed = explode(' ', $header);
        if (count($parsed) == 3 &&
            $parsed[0] == 'Server:' &&
            $parsed[1] == 'vpl-jail-system') {
            self::$lastserverversion = trim($parsed[2]);
        }
        return strlen($header);
    }

    /**
     * Get a cURL handle for the jail server.
     *
     * @param string $server URL of the jail server
     * @param string $request Request to be sent
     * @param bool $fresh If true, force a fresh connection
     * @return resource cURL handle
     * @throws Exception if cURL is not available
     */
    public static function get_curl($server, $request, $fresh = false) {
        if (! function_exists( 'curl_init' )) {
            throw new Exception( 'PHP cURL required' );
        }
        $plugincfg = get_config('mod_vpl');
        $ch = curl_init();
        $contenttype = $request[0] == '{' ? 'application/json' : 'text/xml';
        curl_setopt( $ch, CURLOPT_URL, $server );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, [
                "Content-type: {$contenttype};charset=UTF-8",
                'User-Agent: VPL ' . vpl_get_version(),
        ] );
        self::$lastserverversion = '';
        curl_setopt( $ch, CURLOPT_HEADERFUNCTION, 'vpl_jailserver_manager::parse_headers' );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $request );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
        if ($fresh) {
            curl_setopt( $ch, CURLOPT_FRESH_CONNECT, true );
        }
        if (@$plugincfg->acceptcertificates) {
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        }
        if (isset( $plugincfg->proxy ) && strlen( $plugincfg->proxy ) > 7) {
            curl_setopt( $ch, CURLOPT_PROXY, $plugincfg->proxy );
        }
        return $ch;
    }

    /**
     * Last JSONRPC id used.
     *
     * @var string
     */
    static private $lastjsonrpcid = '';

    /**
     * Generate a new JSONRPC id.
     *
     * @return string
     */
    public static function generate_jsonrpcid() {
        global $USER;
        $idtime = hrtime();
        self::$lastjsonrpcid = $USER->id . '-' . $idtime[0] . '-' . $idtime[1];
    }

    /**
     * Get the JSONRPC id.
     *
     * @return string the JSONRPC id
     */
    public static function get_jsonrpcid() {
        return self::$lastjsonrpcid;
    }

    /**
     * Encode action and data as JSONRPC adding an automatic id.
     *
     * @param string $method
     * @param object $data
     * @return string
     */
    public static function jsonrpc_encode($method, $data) {
        $rpcobject = new stdclass;
        if (false) { // TODO remove when jail servers fixes correponding bug.
            $rpcobject->jsonrpc = "2.0";
        }
        $rpcobject->method = $method;
        $rpcobject->params = $data;
        self::generate_jsonrpcid();
        $rpcobject->id = self::get_jsonrpcid();
        return json_encode($rpcobject, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the response from a jail server.
     *
     * @param string $server URL of the jail server
     * @param string $request Request to be sent
     * @param string $error Error message if any
     * @param bool $fresh If true, force a fresh connection
     * @return array|false Response from the jail server or false on error
     */
    public static function get_response($server, $request, &$error = null, $fresh = false) {
        $ch = self::get_curl( $server, $request, $fresh );
        $rawresponse = curl_exec( $ch );
        if ($rawresponse === false) {
            $error = 'request failed: ' . s( curl_error( $ch ) );
            curl_close( $ch );
        } else {
            $error = '';
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close( $ch );
            if ( $httpcode != 200) {
                $error = "HTTP Status Code: {$httpcode}";
                if ($httpcode == 404) {
                    $error .= " - Bad URLPATH?";
                } else if ($httpcode >= 400 && $httpcode < 500) {
                    $error .= " - Client Error";
                } else if ($httpcode >= 500) {
                    $error .= " - Internal Jail Server Error";
                }
            } else if ($rawresponse[0] == '{') {
                $response = json_decode($rawresponse, null, 512, JSON_INVALID_UTF8_SUBSTITUTE);
                if (json_last_error() != JSON_ERROR_NONE) {
                    $error = 'JSONRPC response is fault: ' . json_last_error_msg();
                } else {
                    if ($response->id != self::get_jsonrpcid()) {
                        $error = 'JSONRPC response mismatch ID';
                    } else {
                        return (array) ($response->result);
                    }
                }
            } else {
                $xmlrpcdecode = 'xmlrpc_decode';
                if (! function_exists($xmlrpcdecode)) {
                    $error = 'Requires execution server version >= 3 or PHP with XML-RPC support';
                } else {
                    $response = $xmlrpcdecode( $rawresponse, "UTF-8" );
                    if (is_array( $response )) {
                        $xmlrpcisfault = 'xmlrpc_is_fault';
                        if ($xmlrpcisfault( $response )) {
                            $error = 'XML-RPC is fault: ' . $response["faultString"];
                        } else {
                            return $response;
                        }
                    } else {
                        $rawresponse = mb_substr($rawresponse, 0, 40);
                        $error = 'HTTP error ' . $rawresponse;
                    }
                }
            }
        }
        return false;
    }
    /**
     * Check if the server is tagged as down.
     *
     * @param string $server URL of the server
     * @return boolean true if the server is checkable, false if it is down
     */
    private static function is_checkable(string $server) {
        global $DB;
        $info = $DB->get_record( self::TABLE, [
                'serverhash' => self::get_hash($server),
                'server' => $server,
        ] );
        if ($info != null) {
            if ($info->lastfail + self::RECHECK > time()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Tag the server as down.
     *
     * @param string $server URL of the server
     * @param string $strerror Error message to be stored
     * @return void
     */
    private static function server_fail(string $server, string $strerror) {
        global $DB;
        $buggyserver = $strerror == get_string('message::bad_jailserver', VPL);
        if ($strerror == null) {
            $strerror = '';
        }
        $info = $DB->get_record( self::TABLE, [
                'serverhash' => self::get_hash($server),
                'server' => $server,
        ] );
        if ($info != null) {
            $info->lastfail = time();
            $info->laststrerror = $strerror;
            $info->nfails ++;
            $info->nbusy = $buggyserver ? -1000000 : $info->nbusy;
            vpl_truncate_jailservers( $info );
            $DB->update_record( self::TABLE, $info );
        } else {
            $info = new stdClass();
            $info->server = $server;
            $info->lastfail = time();
            $info->laststrerror = $strerror;
            $info->nfails = 1;
            $info->serverhash = self::get_hash($server);
            $info->nbusy = $buggyserver ? -1000000 : 0;
            vpl_truncate_jailservers( $info );
            $DB->insert_record( self::TABLE, $info );
        }
    }

    /**
     * Return the defined server list
     *
     * @param string $localserverlisttext List of local server in text
     * @return array of servers
     */
    public static function get_server_list(string $localserverlisttext) {
        $plugincfg = get_config('mod_vpl');
        $nllocal = vpl_detect_newline( $localserverlisttext );
        $nlglobal = vpl_detect_newline( $plugincfg->jail_servers );
        $tempserverlist = array_merge( explode( $nllocal, $localserverlisttext ), explode( $nlglobal, $plugincfg->jail_servers ) );
        $serverlist = [];
        // Clean temp server list and search for 'end_of_jails'.
        foreach ($tempserverlist as $server) {
            $server = trim( $server );
            if ($server > '' && $server[0] != '#') {
                if (strtolower( $server ) == 'end_of_jails') {
                    break;
                } else {
                    $serverlist[] = $server;
                }
            }
        }
        return $serverlist;
    }

    /**
     * Returns action request XMLRPC or JSONRPC.
     *
     * @param string $action
     * @param object $data
     * @return string
     */
    public static function get_action_request(string $action, object $data): string {
        global $CFG;
        $plugincfg = get_config('mod_vpl');
        if ( empty($plugincfg->use_xmlrpc) ) {
            $plugincfg->use_xmlrpc = false;
        }
        $xmlrpcencoderequest = 'xmlrpc_encode_request';
        if ($plugincfg->use_xmlrpc && function_exists($xmlrpcencoderequest)) {
            $outputoptions = [
                'escaping' => 'markup',
                'encoding' => 'UTF-8',
                'verbosity' => 'newlines_only',
            ];
            return $xmlrpcencoderequest( $action, $data, $outputoptions);
        } else {
            return self::jsonrpc_encode( $action, $data);
        }
    }

    /**
     * Returns available request XMLRPC or JSONRPC.
     * @param int $maxmemory Maximum memory in bytes for the request.
     * @return string
     */
    public static function get_available_request(int $maxmemory): string {
        $data = new stdClass();
        $data->maxmemory = $maxmemory;
        return self::get_action_request('available', $data);
    }

    /**
     * Return a valid server to be used, May tag some servers as faulty
     *
     * @param int $maxmemory Required
     * @param string $localserverlisttext List of local server in text.
     * @param ?string $feedback Info about jail servers response
     * @return string
     */
    public static function get_server(int $maxmemory, string $localserverlisttext = '',
                                      ?string &$feedback = null): string {
        $serverlist = self::get_server_list( $localserverlisttext );
        shuffle( $serverlist );
        $requestready = self::get_available_request($maxmemory);
        $feedback = '';
        $error = '';
        $planb = [];
        foreach ($serverlist as $server) {
            if (self::is_checkable( $server )) {
                $response = self::get_response( $server, $requestready, $error );
                if ($response === false) {
                    self::server_fail( $server, $error );
                    $feedback .= parse_url( $server, PHP_URL_HOST ) . ' ' . $error . "\n";
                } else if (! isset( $response['status'] )) {
                    self::server_fail( $server, $error );
                    $feedback .= parse_url( $server, PHP_URL_HOST ) . " protocol error (No status)\n";
                } else if (self::get_last_server_version() > '' && self::get_last_server_version() < '4.0.3') {
                    self::server_fail( $server, get_string('message::bad_jailserver', VPL));
                    $feedback .= parse_url( $server, PHP_URL_HOST ) . " not available.\n";
                } else {
                    if ($response['status'] == 'ready') {
                        return $server;
                    }
                }
            } else {
                $planb[] = $server;
            }
        }
        foreach ($planb as $server) {
            $response = self::get_response( $server, $requestready, $error, true );
            if ($response === false) {
                self::server_fail( $server, $error );
                $feedback .= parse_url( $server, PHP_URL_HOST ) . ' ' . $error . "\n";
            } else if (! isset( $response['status'] )) {
                self::server_fail( $server, $error );
                $feedback .= parse_url( $server, PHP_URL_HOST ) . " protocol error (No status)\n";
            } else {
                if ($response['status'] == 'ready') {
                    return $server;
                }
            }
        }
        return false;
    }

    /**
     * Check if a server is located in a private network
     * Return true ==> private IP
     *
     * @param string $url to server
     * @return bool
     */
    public static function is_private_host(string $url): bool {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        $hostname = parse_url( $url, PHP_URL_HOST );
        if ($hostname === false) {
            return false;
        }
        $name = $hostname . '.';
        $ip = gethostbyname( $name );
        if ($ip != $name) {
            $private = '10., 127., 172.16-31, 192.168., 169.254., 224-239, 240.';
            return address_in_subnet( $ip, $private );
            // IPv6 not implemented fc00::/7 fe80::/10 .
        }
        return true;
    }

    /**
     * Clear servers table and check for every one again
     *
     * @param string $localserverlisttext List of local servers
     * @return array of server object with info about server status
     */
    public static function check_servers(string $localserverlisttext = ''): array {
        global $DB;
        $requestready = self::get_available_request(1024 * 10);
        $serverlist = array_unique( self::get_server_list( $localserverlisttext ) );
        $feedback = [];
        foreach ($serverlist as $server) {
            $status = '';
            $response = self::get_response( $server, $requestready, $status );
            $params = [ 'serverhash' => self::get_hash($server), 'server' => $server ];
            $info = $DB->get_record( self::TABLE, $params);
            if ($info == null) {
                $info = new stdClass();
                $info->server = $server;
                $info->lastfail = null;
                $info->laststrerror = '';
                $info->nfails = 0;
                $info->serverhash = self::get_hash($server);
                $info->nbusy = 0;
            }
            if ($response === false) {
                $info->offline = true;
                self::server_fail( $server, $status );
            } else {
                if (self::get_last_server_version() > '' && self::get_last_server_version() < '4.0.3') {
                    $info->offline = true;
                    $status = get_string('message::bad_jailserver', VPL);
                } else {
                    $info->offline = false;
                    $status = s( $response['status'] );
                }
            }
            $info->current_status = $status;

            $feedback[] = $info;
        }
        return $feedback;
    }

    /**
     * Return the https URL servers list
     *
     * @param string $localserverlisttext List of local server in text, default ''
     * @return array of URLs
     */
    public static function get_https_server_list(string $localserverlisttext = ''): array {
        $requestready = self::get_available_request(1024 * 10);
        $error = '';
        $serverlist = array_unique( self::get_server_list( $localserverlisttext ) );
        $list = [];
        foreach ($serverlist as $server) {
            if (self::is_checkable( $server )) {
                $response = self::get_response( $server, $requestready, $error );
                if ($response === false) {
                    self::server_fail( $server, $error );
                } else if (! isset( $response['status'] )) {
                    self::server_fail( $server, $error );
                } else {
                    if ($response['status'] == 'ready') {
                        $parsed = parse_url( $server );
                        $list[] = 'https://' . $parsed['host'] . ':' . $response['secureport'] . '/OK';
                    }
                }
            }
        }
        return $list;
    }

    /**
     * Get server URL hash
     *
     * @param string $server $URL to generate hash
     * @return int
     */
    private static function get_hash(string $server): int {
        $md = substr(md5($server), -7);
        return hexdec( $md );
    }
}
