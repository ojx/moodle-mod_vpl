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
 * Download all submissions of an activity in zip file
 *
 * @package mod_vpl
 * @copyright 2012 onwards Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>
 */

define( 'NO_DEBUG_DISPLAY', true );

require_once(dirname(__FILE__).'/../../../config.php');
require_once(dirname(__FILE__).'/../locallib.php');
require_once(dirname(__FILE__).'/../vpl.class.php');
require_once(dirname(__FILE__).'/../vpl_submission_CE.class.php');

global $CFG;

/**
 * Sanitize zip directory name
 *
 * @param string $name Directory name
 *
 * @return void
 */
function vpl_user_zip_dirname( $name ) {
    // Prepare name.
    $name = trim( $name );
    $name = str_replace( '?', '_', $name );
    $name = str_replace( '.', '_', $name );
    $name = str_replace( ',', '_', $name );
    $name = str_replace( ':', '_', $name );
    $name = str_replace( '*', '_', $name );
    $name = str_replace( '\\', '_', $name );
    $name = str_replace( '<', '_', $name );
    $name = str_replace( '>', '_', $name );
    $name = str_replace( '|', '_', $name );
    $name = str_replace( '/', '_', $name );
    return $name;
}

/**
 * Adds new files to the zip file.
 * Returns bytes archived
 *
 * @param ZipArchive         $zip        Object that represents a zip file.
 * @param string             $sourcedir  Source directory name
 * @param string             $zipdirname Zip directory name
 * @param file_group_process $fgm        Object that manages group of files
 * @param string             $ziperrors  Output message if error
 *
 * @return int Bytes archived
 */
function vpl_add_files_to_zip($zip, $sourcedir, $zipdirname, $fgm, &$ziperrors) {
    $total = 0;
    foreach ($fgm->getFileList() as $filename) {
        $source = file_group_process::encodeFileName( $filename );
        $filepathorigen = $sourcedir . $source;
        $filepathtarget = $zipdirname . $filename;
        if ( ! file_exists($filepathorigen) ) {
            $ziperrors .= 'Warning: file "'.$filepathorigen . "\" does not exists\n";
            $zip->addFromString($filepathtarget, '');
            continue;
        }
        $data = file_get_contents($filepathorigen);
        $total += strlen($data);
        if ( ! $zip->addFromString($filepathtarget, $data) ) {
            $ziperrors .= 'File "'.$filepathorigen . '" in "' . $filepathtarget . '" ';
            $ziperrors .= 'generate ' . $zip->getStatusString () ."\n";
        }
    }
    return $total;
}

/**
 * Adds new files to the zip file.
 * Returns bytes archived
 *
 * @param ZipArchive $zip Object that represents a zip file.
 * @param mod_vpl_submission_CE $submission Submission object
 * @param string $zipdirname Zip directory name
 *
 * @return int Bytes archived
 */
function vpl_add_ce_to_zip($zip, $submission, $zipdirname) {
    $total = 0;
    $instance = $submission->get_instance();
    $cecg = $submission->getce();
    $cecg['gradecomments'] = $submission->get_grade_comments();
    $cecg['usercomments'] = $instance->comments;
    $cecg['grade'] = $instance->grade;
    if ($cecg['compilation'] !== 0 || $cecg['executed'] == 1 ||
        $cecg['gradecomments'] . $cecg['usercomments'] . $cecg['grade'] > '') {
        if ( $cecg['compilation'] !== 0 ) {
            $zip->addFromString( $zipdirname. 'compilation' . '.txt', $cecg['compilation']);
            $total += strlen($cecg['compilation']);
        }
        if ( $cecg['executed'] == 1 ) {
            $zip->addFromString( $zipdirname . 'execution' . '.txt', $cecg['execution']);
            $total += strlen($cecg['execution']);
        }
        foreach (['gradecomments', 'usercomments', 'grade'] as $ele) {
            if ( ! empty($cecg[$ele])) {
                $zip->addFromString( $zipdirname . $ele . '.txt', $cecg[$ele]);
                $total += strlen($cecg[$ele]);
            }
        }
    }
    return $total;
}

require_login();
$id = required_param( 'id', PARAM_INT );
$all = optional_param( 'all', 0, PARAM_INT );

/**
 * @var int Size trigger to close the zip file and reopen it.
 */
const SIZE_TRIGGER = 64 * 1024 * 1024; // 64Mb.
$vpl = new mod_vpl( $id );
$cm = $vpl->get_course_module();
$vpl->require_capability( VPL_SIMILARITY_CAPABILITY );
\mod_vpl\event\vpl_all_submissions_downloaded::log( $vpl );
// Get students.
$currentgroup = groups_get_activity_group( $cm );
$extraname = '';
if (! $currentgroup) {
    $currentgroup = '';
} else {
    $extraname = ' ' . groups_get_group_name( $currentgroup );
}
if ($vpl->is_group_activity()) {
    $idfiels = 'groupid';
    $list = groups_get_all_groups($vpl->get_course()->id, 0, $cm->groupingid);
} else {
    $list = $vpl->get_students($currentgroup, 'u.username');
    $idfiels = 'userid';
}

if ($all) {
    $asortedsubmissions = $vpl->all_user_submission();
} else {
    $asortedsubmissions = $vpl->all_last_user_submission();
}
// Organize information by user id.
$submissions = [];
foreach ($asortedsubmissions as $instance) {
    if (! isset($submissions[$instance->$idfiels])) {
        $submissions[$instance->$idfiels] = [];
    }
    $submissions[$instance->$idfiels][] = $instance;
}

// Get all information by user.
$alldata = [];
foreach ($list as $uginfo) {
    if (! isset($submissions[$uginfo->id])) {
        continue;
    }
    $data = new stdClass();
    $data->uginfo = $uginfo;
    // When group activity => change leader object lastname to groupname for order porpouse.
    if ($vpl->is_group_activity()) {
        $data->uginfo->firstname = 'Group';
        $data->uginfo->lastname = $uginfo->name;
        $data->uginfo->username = '';
    }
    $usersubmissions = [];
    foreach ($submissions[$uginfo->id] as $subinstance) {
        $usersubmissions[] = new mod_vpl_submission_CE( $vpl, $subinstance );
    }
    $data->submissions = $usersubmissions;
    $alldata[] = $data;
}

$zip = new ZipArchive();
$dir = $CFG->dataroot . '/temp/vpl';
if (! file_exists($dir)) {
    mkdir($dir, $CFG->directorypermissions, true);
}
$zipfilename = tempnam( $dir, 'zip' );
if ( $zipfilename === false || ! file_exists($zipfilename) ) {
    throw new moodle_exception('cannotopenzip');
}
if ($zip->open($zipfilename, ZipArchive::OVERWRITE) === true) {
    $ziperrors = '';
    $nsubmissions = 0;
    $sizearchived = 0;
    foreach ($alldata as $data) {
        $user = $data->uginfo;
        $zipdirname = $user->lastname . ' ' . $user->firstname . ' ' . $user->id . ' ' . $user->username;
        $zipdirname = vpl_user_zip_dirname($zipdirname);
        // Create directory.
        $zip->addEmptyDir( $zipdirname );
        $zipdirname .= '/';
        foreach ($data->submissions as $submission) {
            $nsubmissions ++;
            $date = date("Y-m-d-H-i-s", $submission->get_instance()->datesubmitted );
            $zipsubdirname = $zipdirname. $date . '/';
            $fgm = $submission->get_submitted_fgm();
            $sourcedir = $submission->get_submission_directory();
            $sizearchived += vpl_add_files_to_zip($zip, $sourcedir, $zipsubdirname, $fgm, $ziperrors);
            $zipsubdirname = $zipdirname . $date . '.ceg/';
            $sizearchived += vpl_add_ce_to_zip($zip, $submission, $zipsubdirname);
        }
        // To keep de memory used low, the zip file is closed and reopened.
        if ($sizearchived > SIZE_TRIGGER) {
            $zip->close();
            $zip = new ZipArchive();
            $zip->open($zipfilename);
            $sizearchived = 0;
        }
        unset($data->submissions);
    }
    $date = date(DATE_W3C);
    $nusers = count($alldata);
    $zip->addFromString('Report.txt', "Date: $date\nNumber of users: $nusers\nNumber of submissions: $nsubmissions");
    if ( $ziperrors > '' ) {
        $zip->addFromString( 'Errors.txt', $ziperrors );
    }
    $zip->close();
    vpl_output_zip($zipfilename, $vpl->get_instance()->name . $extraname);
} else {
    throw new moodle_exception('cannotopenzip');
}
