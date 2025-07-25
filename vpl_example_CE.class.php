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
 * Class for Example activitie
 * @package mod_vpl
 * @copyright 2012 onwards Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>
 */

defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/vpl_submission_CE.class.php');

/**
 * Class mod_vpl_example_CE
 *
 * This class to manage example activities using a fake submission.
 * It extends the mod_vpl_submission_CE class, which handles the compilation execution
 * of submissions in VPL activities.
 *
 * @package mod_vpl
 */
class mod_vpl_example_CE extends mod_vpl_submission_CE {
    /**
     * Constructor
     *
     * @param mod_vpl $vpl instance of mod_vpl
     */
    public function __construct($vpl) {
        global $USER;
        $fake = new stdClass();
        $fake->userid = $USER->id;
        $fake->id = 0;
        $fake->vpl = $vpl->get_instance()->id;
        $fake->datesubmitted = time() - 60;
        $fake->comments = '';
        $fake->nevaluations = 0;
        $fake->save_count = 1;
        $fake->run_count = 0;
        $fake->debug_count = 0;
        $fake->groupid = 0;
        parent::__construct($vpl, $fake);
    }

    /**
     * Cache file group used as submitted files.
     * @var object
     */
    protected $submittedfgm;

    /**
     *
     * @return object file group manager for example files
     */
    public function get_submitted_fgm() {
        if (! isset($this->submittedfgm)) {
            $this->submittedfgm = $this->vpl->get_required_fgm();
        }
        return $this->submittedfgm;
    }

    /**
     * Save Compilation Execution result. Removed
     *
     * @param array $result response from server
     * @return void
     */
    public function savece($result) {
    }
}
