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

global $USER;

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/locallib.php');
require_once(dirname(__FILE__) . '/form/mod_lips_category_form.php');

$id = optional_param('id', 0, PARAM_INT);
$n = optional_param('n', 0, PARAM_INT);
$action = optional_param('action', 0, PARAM_TEXT);
$originv = optional_param('originV', "index", PARAM_TEXT);
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

add_to_log($course->id, 'lips', 'action', "action.php?id={$cm->id}", $lips->name, $cm->id);

switch ($action) {
    case "deleteCategory":
        if (has_role('administration')) {
            $categoryid = optional_param('categoryId', 0, PARAM_INT);
            $categorydetails = get_category_details($categoryid);

            delete_category($categoryid);

            // Insert the notifications.
            $lips = get_current_instance();
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
            insert_notification($lips->id, $userdetails->id, 'notification_category_deleted', time(), $userdetails->id, null, null, null, $categorydetails->category_name);
            $followers = fetch_followers($userdetails->id);
            foreach ($followers as $follower) {
                insert_notification($lips->id, $follower->follower, 'notification_category_deleted', time(), $userdetails->id, null, null, null, $categorydetails->category_name);
            }

            if ($originaction == null) {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
            } else {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
            }
        }
        break;

    case "deleteProblem":
        if (has_role('administration')) {
            $problemid = optional_param('problemId', null, PARAM_INT);
            $categoryid = optional_param('categoryId', null, PARAM_INT);
            $problemdetails = get_problem_details_array(array('id' => $problemid));

            if (is_author($problemid, $USER->id)) {
                delete_problem($problemid);
            }

            // Insert the notifications.
            $lips = get_current_instance();
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
            insert_notification($lips->id, $userdetails->id, 'notification_problem_deleted', time(), $userdetails->id, null, null, null, $problemdetails->problem_label);
            $followers = fetch_followers($userdetails->id);
            foreach ($followers as $follower) {
                insert_notification($lips->id, $follower->follower, 'notification_problem_deleted', time(), $userdetails->id, null, null, null, $problemdetails->problem_label);
            }

            if ($categoryid != null && !empty($categoryid)) {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'categoryId' => $categoryid)));
            }

            if ($originaction == null) {
                if ($originv == 'problem') {
                    redirect(new moodle_url('view.php', array('id' => $cm->id)));
                } else {
                    redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
                }
            } else {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
            }
        }
        break;

    case "deleteProblems":
        if (has_role('administration')) {
            $serializedidproblems = optional_param("idproblems", null, PARAM_TEXT);
            foreach (unserialize($serializedidproblems) as $idproblem) {
                delete_problem($idproblem);
            }
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => 'administration', 'action' => 'problem_category_select_delete')));
        }
        break;

    case 'follow':
        $tofollow = optional_param('to_follow', 0, PARAM_INT);
        $originuser = optional_param('originUser', null, PARAM_TEXT);

        // Follow the user.
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

        // Unfollow the user.
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
        unfollow($userdetails->id, $tounfollow);

        if ($originuser == null) {
            if ($originaction == null) {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
            } else {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
            }
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'id_user' => $originuser)));
        }
        break;

    case 'testing':
        if (has_role('administration')) {
            $totest = optional_param('to_test', 0, PARAM_INT);

            // Go to testing mode.
            to_testing_mode($totest);

            if ($originaction == null) {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
            } else {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
            }
        }
        break;

    case 'untesting':
        if (has_role('administration')) {
            $tountest = optional_param('to_untest', 0, PARAM_INT);

            // Go to display mode.
            to_display_mode($tountest);

            if ($originaction == null) {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv)));
            } else {
                redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
            }
        }
        break;

    case 'accept_challenge':
        $challengeid = optional_param('challenge_id', 0, PARAM_INT);

        // Accept the challenge.
        accept_challenge($challengeid);

        if ($originv == null && $originaction == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id)));
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
        }
        break;

    case 'refuse_challenge':
        $challengeid = optional_param('challenge_id', 0, PARAM_INT);

        // Accept the challenge.
        refuse_challenge($challengeid);

        if ($originv == null && $originaction == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id)));
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
        }
        break;

    case 'cancel_challenge':
        $challengeid = optional_param('challengeId', 0, PARAM_INT);

        // Cancel the challenge.
        cancel_challenge($challengeid);

        if ($originv == null && $originaction == null) {
            redirect(new moodle_url('view.php', array('id' => $cm->id)));
        } else {
            redirect(new moodle_url('view.php', array('id' => $cm->id, 'view' => $originv, 'action' => $originaction)));
        }
        break;
}

redirect(new moodle_url('view.php', array('id' => $cm->id)));