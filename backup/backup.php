<?php

define('CLI_SCRIPT', true );

require_once('../../../config.php');
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');

//require_login($course, null, $cm);
// require_capability('moodle/backup:backupactivity', context_module::instance($cm->id));

global $USER;

// Get current module id. 
// $cm = get_coursemodule_from_id('lips', optional_param('id', 0, PARAM_INT), 0, false, MUST_EXIST);
// $moduleid = $cm->id;
$moduleid = 11;

// Get current user.
// $userid = $USER->id;
$userid = 2;

$bc = new backup_controller(backup::TYPE_1ACTIVITY, $moduleid, backup::FORMAT_MOODLE,
                            backup::INTERACTIVE_YES, backup::MODE_GENERAL, $userid);
$bc->finish_ui();
$bc->execute_plan();
$bc->get_results();