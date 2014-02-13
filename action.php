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

$id = optional_param('id', 0, PARAM_INT);
$n = optional_param('n', 0, PARAM_INT);
$action = optional_param('action', 0, PARAM_TEXT);
$originv = optional_param('originV', "index", PARAM_TEXT);
$originaction = optional_param('originAction', null, PARAM_TEXT);

global $USER;

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

        if ($originaction == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
        }
        break;
    case "deleteProblem":
        $problemid = optional_param('problemId', null, PARAM_INT);
        $categoryid = optional_param('categoryId', null, PARAM_INT);
        if (is_author($problemid, $USER->id)) {
            delete_problem($problemid);
        }
        if ($categoryid != null && !empty($categoryid)) {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'categoryId' => $categoryid)));
        }
        if ($originaction == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
        }
        break;

    case 'follow':
        $tofollow = optional_param('to_follow', 0, PARAM_INT);
        $originuser = optional_param('originUser', null, PARAM_TEXT);

        // Follow the user
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
        follow($userdetails->id, $tofollow);

        if ($originuser == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'id_user' => $originuser)));
        }
        break;

    case 'unfollow':
        $tounfollow = optional_param('to_unfollow', 0, PARAM_INT);
        $originuser = optional_param('originUser', null, PARAM_TEXT);

        // Unfollow the user
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
        unfollow($userdetails->id, $tounfollow);

        if ($originuser == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'id_user' => $originuser)));
        }
        break;

    case 'testing':
        $totest = optional_param('to_test', 0, PARAM_INT);

        // Go to testing mode
        to_testing_mode($totest);

        if ($originaction == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
        }
        break;

    case 'untesting':
        $tountest = optional_param('to_untest', 0, PARAM_INT);

        // Go to display mode
        to_display_mode($tountest);

        if ($originaction == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
        }
        break;
}

redirect(new moodle_url('view.php', array('id' => $cm->id)));