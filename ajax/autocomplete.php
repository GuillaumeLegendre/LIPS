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

/**
 * @package     mod_lips
 * @subpackage  backup-moodle2
 * @category    backup
 * @copyright   2014 LIPS
 *
 * @author Valentin Got
 * @author Guillaume Legendre
 * @author Mickael Ohlen
 * @author Anaïs Picoreau
 * @author Julien Senac
 *
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);
header('content-type: text/html; charset=utf-8');

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once(dirname(dirname(__FILE__)) . '/lib.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');

global $USER, $DB;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'solved_problems':
            if (isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $problems = $DB->get_records_sql('SELECT problem_id, problem_label FROM (
                    (
                        SELECT mlp.id AS problem_id, mlp.problem_label
                        FROM mdl_lips_problem_solved mls
                        JOIN mdl_lips_problem mlp ON mlp.id = mls.problem_solved_problem 
                        WHERE problem_solved_user = ' . $userid . '
                        AND problem_testing = 0
                    )
                    UNION ALL
                    (
                        SELECT mlp.id AS problem_id, mlp.problem_label
                        FROM mdl_lips_problem_failed mls
                        JOIN mdl_lips_problem mlp ON mlp.id = mls.problem_failed_problem 
                        WHERE problem_failed_user = ' . $userid . '
                        AND problem_testing = 0
                    )) t GROUP BY problem_id');

                $problemstable = array();
                foreach ($problems as $problem) {
                    $problemstable[$problem->problem_id] = $problem->problem_label;
                }

                echo json_encode($problemstable);
            }
            break;

        case 'followed_users':
            if (isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $users = $DB->get_records_sql("
                    SELECT mlu.id, firstname, lastname
                    FROM mdl_lips_follow mlf, mdl_lips_user mlu, mdl_user mu
                    WHERE mlf.followed = mlu.id
                    AND mu.id = mlu.id_user_moodle
                    AND mlf.follower = $userid");

                $users_table = array();
                foreach ($users as $user) {
                    $users_table[$user->id] = $user->firstname . ' ' . $user->lastname;
                }

                echo json_encode($users_table);
            }
            break;

        case 'problems_by_category':
            if (isset($_POST['categoryid'])) {
                $categoryid = $_POST['categoryid'];

                $problems = $DB->get_records_sql("
                    SELECT mlp.id, problem_label
                    FROM mdl_lips_problem mlp
                    WHERE problem_category_id = $categoryid
                    AND (
                        problem_testing = 0
                        OR problem_testing = 1 AND problem_creator_id = " . $USER->id . "
                    )
                    GROUP BY mlp.id");

                $problemstable = array();
                foreach ($problems as $problem) {
                    $problemstable[$problem->id] = $problem->problem_label;
                }

                echo json_encode($problemstable);
            }
            break;

        case 'users_problem_solutions':
            if (isset($_POST['problemid'])) {
                $problemid = $_POST['problemid'];

                $users = $DB->get_records_sql("
                    SELECT problem_solved_user, firstname, lastname
                    FROM mdl_lips_problem_solved mlps
                    JOIN mdl_user mu ON mu.id = mlps.problem_solved_user
                    AND problem_solved_problem = $problemid
                    GROUP BY problem_solved_user");

                $users_table = array();
                foreach ($users as $user) {
                    $users_table[$user->id] = $user->firstname . ' ' . $user->lastname;
                }

                echo json_encode($users_table);
            }
            break;

        case 'users':
            $users = $DB->get_records_sql("
                SELECT mlu.id, firstname, lastname
                FROM mdl_lips_user mlu
                JOIN mdl_user mu ON mlu.id_user_moodle = mu.id");

            $users_table = array();
            foreach ($users as $user) {
                $users_table[$user->id] = $user->firstname . ' ' . $user->lastname;
            }

            echo json_encode($users_table);
            break;

        case 'received_challenges_problems':
            if (isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $problems = $DB->get_records_sql("
                    SELECT mlp.id, problem_label
                    FROM mdl_lips_challenge mlc
                    JOIN mdl_lips_problem mlp ON mlc.challenge_problem = mlp.id
                    AND challenge_to = $userid");

                $problemstable = array();
                foreach ($problems as $problem) {
                    $problemstable[$problem->id] = $problem->problem_label;
                }

                echo json_encode($problemstable);
            }
            break;

        case 'received_challenges_users':
            if (isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $users = $DB->get_records_sql("
                    SELECT mlu.id, firstname, lastname
                    FROM mdl_lips_challenge mlc
                    JOIN mdl_lips_user mlu ON mlc.challenge_from = mlu.id
                    JOIN mdl_user mu ON mlu.id_user_moodle = mu.id
                    AND challenge_to = $userid");

                $users_table = array();
                foreach ($users as $user) {
                    $users_table[$user->id] = $user->firstname . ' ' . $user->lastname;
                }

                echo json_encode($users_table);
            }
            break;

        case 'sent_challenges_problems':
            if (isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $problems = $DB->get_records_sql("
                    SELECT mlp.id, problem_label
                    FROM mdl_lips_challenge mlc
                    JOIN mdl_lips_problem mlp ON mlc.challenge_problem = mlp.id
                    AND challenge_from = $userid");

                $problemstable = array();
                foreach ($problems as $problem) {
                    $problemstable[$problem->id] = $problem->problem_label;
                }

                echo json_encode($problemstable);
            }
            break;

        case 'sent_challenges_users':
            if (isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $users = $DB->get_records_sql("
                    SELECT mlu.id, firstname, lastname
                    FROM mdl_lips_challenge mlc
                    JOIN mdl_lips_user mlu ON mlc.challenge_to = mlu.id
                    JOIN mdl_user mu ON mlu.id_user_moodle = mu.id
                    AND challenge_from = $userid");

                $users_table = array();
                foreach ($users as $user) {
                    $users_table[$user->id] = $user->firstname . ' ' . $user->lastname;
                }

                echo json_encode($users_table);
            }
            break;
    }
}