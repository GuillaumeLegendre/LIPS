<?php
define('AJAX_SCRIPT', true);
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
$id = $_GET['id'];
$PAGE->set_url('/mod/lips/get_categories_by_instance.php', array('id' => $id));
$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
$categories = $DB->get_records('lips_category', array('id_language' => $id));
echo $OUTPUT->header();
echo json_encode($categories);
echo $OUTPUT->footer();