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

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__).'/similarity_base.class.php');

/**
 * Class to find and show clusters of similar files
 *
 * @package mod_vpl
 * @copyright 2012 Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>
 */
class vpl_clusters {
    /**
     * @var array Clusters of similar files.
     *
     * This is an associative array where the key is the cluster id and the value
     * is an array of file pairs that belong to that cluster.
     */
    protected $clusters; // Array of clusters.

    /**
     * @var array Number of members in each cluster.
     *
     * This is an associative array where the key is the cluster id and the value
     * is the number of members in that cluster.
     */
    protected $cmembers;

    /**
     * @var array Adjacency list of files.
     *
     * This is an array where the key is the file id (fid) and the value is an
     * associative array of other file ids that are similar to the key file.
     */
    protected $adjlist;

    /**
     * @var int Maximum number of members in a cluster.
     */
    const MAX_MEMBERS = 5;

    /**
     * Constructor
     * @param array $selected Array of file pairs to process.
     */
    public function __construct($selected) {
        $this->clusters = [];
        $this->cmembers = [];
        // Identify every file.
        // Set all files to not clustered.
        $fid = 0;
        foreach ($selected as $case) {
            if (! isset( $case->first->fid )) {
                $case->first->cluster = - 1;
                $case->first->fid = $fid ++;
            }
            if (! isset( $case->second->fid )) {
                $case->second->cluster = - 1;
                $case->second->fid = $fid ++;
            }
        }
        $this->adjlist = [];
        for ($i = 0; $i < $fid; $i ++) {
            $row = [];
            $this->adjlist[$i] = $row;
        }
        foreach ($selected as $case) {
            $one = $case->first->fid;
            $other = $case->second->fid;
            $this->adjlist[$one][$other] = true;
            $this->adjlist[$other][$one] = true;
        }
        foreach ($selected as $case) {
            $this->process( $case );
        }
        $this->assign_number();
    }

    /**
     * Assign cluster
     *
     * @param object $pair file pair to process
     */
    public function process($pair) {
        $c1 = $pair->first->cluster;
        $c2 = $pair->second->cluster;
        if ($c1 == $c2) { // Not assigned or same cluster.
            if ($pair->first->cluster == - 1) { // New cluster.
                $newid = count( $this->clusters );
                $pair->first->cluster = $newid;
                $pair->second->cluster = $newid;
                $this->clusters[$newid] = [
                        $pair,
                ];
                $this->cmembers[$newid] = 2;
            } else {
                $this->clusters[$c1][] = $pair;
                $this->cmembers[$c1] ++;
            }
        } else if ($c1 == - 1 || $c2 == - 1) { // One file not assigned.
            if ($c1 == - 1) {
                $cluster = $c2;
                $next = $pair->first->fid;
            } else {
                $cluster = $c1;
                $next = $pair->second->fid;
            }
            if ($this->cmembers[$cluster] >= self::MAX_MEMBERS
                || count( $this->adjlist[$next] ) < $this->cmembers[$cluster] / 2) {
                return;
            }
            $this->cmembers[$cluster] ++;
            $pair->first->cluster = $cluster;
            $pair->second->cluster = $cluster;
            $this->clusters[$cluster][] = $pair;
            if (false) { // Debug zone.
                echo "<h3>Añadimos" . (count( $this->adjlist[$next] )) . " ";
                echo ($this->cmembers[$cluster] / 2) . "</h3>";
                $this->print_cluster( $this->clusters[$cluster], 1);
            }

        } else { // Diferent clusters.
            $one = $pair->first->fid;
            $other = $pair->second->fid;
            $mincluster = min( $c1, $c2 );
            $maxcluster = max( $c1, $c2 );
            // Need clusters fusion?
            if (count( $this->adjlist[$one] ) <= $this->cmembers[$c2] / 2
                || count( $this->adjlist[$other] ) <= $this->cmembers[$c1] / 2
                || ($this->cmembers[$c1] + $this->cmembers[$c2]) > self::MAX_MEMBERS) {
                return;
            }
            $cmax = $this->cmembers[$maxcluster];
            $cmin = $this->cmembers[$mincluster];
            // Select minimum cost fusion.
            if ($cmin > $cmax) {
                $aux = $mincluster;
                $mincluster = $maxcluster;
                $maxcluster = $aux;
            }
            if (false) { // Debug zone.
                echo "<h3>Unimos</h3>";
                $this->print_cluster( $this->clusters[$mincluster], 1);
                $this->print_cluster( $this->clusters[$maxcluster], 2);
            }
            foreach ($this->clusters[$mincluster] as $pairmove) { // Fusion.
                $pairmove->first->cluster = $maxcluster;
                $pairmove->second->cluster = $maxcluster;
                $this->clusters[$maxcluster][] = $pairmove;
            }
            if (false) { // Debug zone.
                $this->print_cluster( $this->clusters[$maxcluster], 1);
            }
            $this->cmembers[$maxcluster] += $this->cmembers[$mincluster];
            $this->cmembers[$mincluster] = 0;
            $this->clusters[$mincluster] = []; // Remove cluster.
        }
    }

    /**
     * Assign file id.
     *
     * This method assigns a unique identifier to a file if it does not already have one.
     * It increments the provided id counter for each new file assigned.
     *
     * @param object $file The file object to assign an id to.
     * @param int $id The current id counter, which will be incremented.
     */
    public function assign_file_id(&$file, &$id) {
        if (! isset( $file->id )) {
            $file->id = $id ++;
        }
    }

    /**
     * Assign cluster numbers to pairs.
     *
     * This method iterates through the clusters and assigns a unique cluster number
     * to each pair of files in clusters that contain two or more pairs.
     */
    public function assign_number() {
        $clusternumber = 1;
        foreach ($this->clusters as $cluster) {
            if (count( $cluster ) > 1) { // Two or more pairs => 3 or more files.
                foreach ($cluster as $pair) {
                    $pair->set_cluster( $clusternumber );
                }
                $clusternumber ++;
            }
        }
    }

    /**
     * Print a cluster.
     *
     * This method prints the details of a cluster, including the files and their
     * similarities, in a formatted table.
     *
     * @param array $cluster The cluster to print, containing pairs of similar files.
     * @param int $clusternumber The number of the cluster being printed.
     */
    public function print_cluster($cluster, $clusternumber) {
        // Assign ids (0..num_files-1) to files.
        foreach ($cluster as $pair) {
            unset( $pair->first->id );
            unset( $pair->second->id );
        }
        $id = 0;
        foreach ($cluster as $pair) {
            $this->assign_file_id( $pair->first, $id );
            $this->assign_file_id( $pair->second, $id );
        }
        // Build matrix.
        $numfiles = $id;
        $files = [];
        $matrix = [];
        for ($i = 0; $i < $numfiles; $i ++) {
            $matrix[] = [];
        }
        foreach ($cluster as $pair) {
            $files[$pair->first->id] = $pair->first;
            $files[$pair->second->id] = $pair->second;
            $matrix[$pair->first->id][$pair->second->id] = $pair;
            $matrix[$pair->second->id][$pair->first->id] = $pair;
        }
        // Reorder files.
        $auxorder = [];
        for ($i = 0; $i < $numfiles; $i ++) {
            $value = PHP_INT_MAX;
            foreach ($matrix[$i] as $pair) {
                $value = min( $value, $pair->get_level() );
            }
            $auxorder[] = $value;
        }
        asort( $auxorder );
        $firstorder = [];
        foreach (array_keys($auxorder) as $file) {
            $firstorder[] = $file;
        }
        $order = [];
        $center = ( int ) ($numfiles / 2);
        $order[$center] = $firstorder[0];
        $pos = 1;
        for ($i = 1; $pos < $numfiles; $i ++) {
            if ($center - $i >= 0 && $pos < $numfiles) {
                $order[$center - $i] = $firstorder[$pos ++];
            }
            if ($center + $i < $numfiles && $pos < $numfiles) {
                $order[$center + $i] = $firstorder[$pos ++];
            }
        }
        // Fill matrix.
        for ($i = 0; $i < $numfiles; $i ++) {
            for ($j = 0; $j < $numfiles; $j ++) {
                if ($i != $j && ! isset( $matrix[$i][$j] )) {
                    $s1 = $files[$i]->similarity1( $files[$j] );
                    $s2 = $files[$i]->similarity2( $files[$j] );
                    $s3 = $files[$i]->similarity3( $files[$j] );
                    $matrix[$i][$j] = new vpl_files_pair( $files[$i], $files[$j], $s1, $s2, $s3 );
                    $matrix[$j][$i] = $matrix[$i][$j];
                }
            }
        }
        // Generate table.
        $table = new html_table();
        $table->head = [
                'info',
                '#',
        ];
        $table->align = [
                'left',
                'right',
        ];
        $table->size = [
                '60',
                '5',
        ];
        for ($i = 0; $i < $numfiles; $i ++) {
            $table->head[] = $i + 1;
            $table->align[] = 'right';
            $table->size[] = '10';
        }
        for ($pi = 0; $pi < $numfiles; $pi ++) {
            $i = $order[$pi];
            $row = [
                    $files[$i]->show_info(),
                    $pi + 1,
            ];
            for ($pj = 0; $pj < $numfiles; $pj ++) {
                $j = $order[$pj];
                if ($i == $j) {
                    $row[] = '';
                } else {
                    $row[] = $matrix[$i][$j]->get_link();
                }
            }
            $table->data[] = $row;
        }
        echo '<a name="clu' . $clusternumber . '"></a>';
        echo '<b>' . s( get_string( 'numcluster', VPL, $clusternumber ) ) . '</b>';
        echo html_writer::table( $table );
    }

    /**
     * Print all clusters.
     *
     * This method iterates through the clusters and prints each one that has
     * more than one file.
     */
    public function print_clusters() {
        $clusternumber = 1;
        foreach ($this->clusters as $cluster) {
            if (count( $cluster ) > 1) { // Three or more files.
                $this->print_cluster( $cluster, $clusternumber );
                $clusternumber ++;
            }
        }
    }
}
