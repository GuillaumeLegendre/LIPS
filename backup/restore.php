<?php
 
define('CLI_SCRIPT', true );
 
require_once('../../../config.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php' );
 
//require_login($course, null, $cm);
// require_capability('moodle/restore:restorecourse', $context);

global $USER;

// Transaction.
// $transaction = $DB->start_delegated_transaction( );
 
// Get current course id. 
// global $PAGE; 
// $context = $PAGE->context;
// $coursecontext = $context->get_course_context();
// $courseid = $coursecontext->instanceid;
$courseid = 2;

// Get current user.
// $userid = $USER->id;
$userid = 2;

$folder = "1215e4296ace2e14c93878f83b9a8b3f/lips.xml"; // as found in: $CFG->dataroot . '/temp/backup/' 
 
// Restore backup into course
$controller = new restore_controller($folder, $courseid, 
        backup::INTERACTIVE_NO, backup::MODE_GENERAL, $userid, backup::TARGET_CURRENT_ADDING);
$controller->execute_precheck();
$controller->execute_plan();
 
// Commit
// $transaction->allow_commit();