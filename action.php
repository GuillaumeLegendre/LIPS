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

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/pagelib.php');
require_once(dirname(__FILE__) . '/locallib.php');
require_once(dirname(__FILE__) . '/mod_lips_category_form.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n = optional_param('n', 0, PARAM_INT); // lips instance ID - it should be named as the first character of the module
$action = optional_param('action', 0, PARAM_TEXT); // lips instance ID - it should be named as the first character of the module
$originv = optional_param('originV', "index", PARAM_TEXT); // lips instance ID - it should be named as the first character of the module
$originaction = optional_param('originAction', null, PARAM_TEXT);

if ($id) {
    $cm = get_coursemodule_from_id('lips', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $lips = $DB->get_record('lips', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $lips = $DB->get_record('lips', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $lips->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('lips', $lips->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

if (!has_role('administration')) {
    redirect(new moodle_url('view.php', array('id' => $cm->id)));
}

add_to_log($course->id, 'lips', 'action', "action.php?id={$cm->id}", $lips->name, $cm->id);

switch ($action) {
    case "deleteCategory":
        $categoryid = optional_param('categoryId', 0, PARAM_INT);

        delete_category($categoryid);

       if($originaction == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
       }
        else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
        }
        break;
}

redirect(new moodle_url('view.php', array('id' => $cm->id)));