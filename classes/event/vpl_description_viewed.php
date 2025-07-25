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
 * Class for logging of vpl description view events
 *
 * @package mod_vpl
 * @copyright 2014 onwards Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>
 */
namespace mod_vpl\event;

defined( 'MOODLE_INTERNAL' ) || die();
require_once(dirname( __FILE__ ) . '/../../locallib.php');

/**
 * Event class for when a description is viewed.
 * This class is used to log the event when a description is viewed.
 */
class vpl_description_viewed extends vpl_base {

    /**
     * Initializes the event.
     * This method is called when the event is created.
     */
    protected function init() {
        parent::init();
        $this->data['crud'] = 'r';
        $this->legacyaction = 'view';
    }

    /**
     * Returns the event description.
     * This method is used to provide a human-readable description of the event.
     *
     * @return string Description of the event.
     */
    public function get_description() {
        return $this->get_description_mod( 'description' );
    }
}
