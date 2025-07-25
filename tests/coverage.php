<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

/**
 * Coverage information for the mod_vpl plugin.
 *
 * @package    mod_vpl
 * @copyright  2022 Juan Carlos Rodríguez-del-Pino <jc.rodriguezdelpino@ulpgc.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Coverage information for the core subsystem.
 *
 * @copyright  2019 Andrew Nicols <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
return new class extends phpunit_coverage_info {
    /** @var array The list of folders relative to the plugin included in coverage report. */
    protected $includelistfolders = [
        'classes/privacy',
        'classes/task',
        'classes/util',
        'similarity',
    ];

    /** @var array The list of files relative to the plugin root included in coverage report. */
    protected $includelistfiles = [
        'externallib.php',
        'lib.php',
        'locallib.php',
        'filegroup.class.php',
        'similarity/similarity_sources.class.php',
        'jail/running_processes.class.php',
        'vpl.class.php',
    ];

    /** @var array The list of folders relative to the plugin root to excludelist in coverage generation. */
    protected $excludelistfolders = [];

    /** @var array The list of files relative to the plugin root to excludelist in coverage generation. */
    protected $excludelistfiles = [
        'externallib.php',
        'lib.php',
        'locallib.php',
        'classes/token.php',
        'classes/token_type.php',
        'classes/assertf.php',
    ];
};
