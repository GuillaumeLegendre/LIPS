<?php
define('AJAX_SCRIPT', true);
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
$id = $_GET['id'];
$PAGE->set_url('/mod/lips/get_problems_by_category.php', array('id'=>$id));
$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
$problems = $DB->get_records('lips_problem', array('problem_category_id' => $id));
echo $OUTPUT->header();
echo json_encode($problems);
echo $OUTPUT->footer();