<?php
define('AJAX_SCRIPT', true);
header('content-type: text/html; charset=utf-8');

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once(dirname(dirname(__FILE__)) . '/lib.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');

global $USER, $DB;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'solved_problems':
            if(isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $problems = $DB->get_records_sql("SELECT mlp.id, problem_label 
                    FROM mdl_lips_problem_solved mlps 
                    JOIN mdl_lips_problem mlp ON mlps.problem_solved_problem = mlp.id 
                    WHERE mlps.problem_solved_user = $userid 
                    GROUP BY mlp.id");

                $problems_table = array();
                foreach ($problems as $problem) {
                    $problems_table[$problem->id] = $problem->problem_label;
                }

                echo json_encode($problems_table);
            }
            break;

        case 'followed_users':
            if(isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $users = $DB->get_records_sql("SELECT mlu.id, firstname, lastname
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
            if(isset($_POST['categoryid'])) {
                $categoryid = $_POST['categoryid'];

                $problems = $DB->get_records_sql("SELECT mlp.id, problem_label 
                    FROM mdl_lips_problem mlp 
                    WHERE problem_category_id = $categoryid 
                    AND (
                        problem_testing = 0 
                        OR problem_testing = 1 AND problem_creator_id = " . $USER->id . "
                    ) 
                    GROUP BY mlp.id");

                $problems_table = array();
                foreach ($problems as $problem) {
                    $problems_table[$problem->id] = $problem->problem_label;
                }

                echo json_encode($problems_table);
            }
            break;

        case 'users_problem_solutions':
            if(isset($_POST['problemid'])) {
                $problemid = $_POST['problemid'];

                $users = $DB->get_records_sql("SELECT problem_solved_user, firstname, lastname 
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
            $users = $DB->get_records_sql("SELECT mlu.id, firstname, lastname 
                FROM mdl_lips_user mlu
                JOIN mdl_user mu ON mlu.id_user_moodle = mu.id");

            $users_table = array();
            foreach ($users as $user) {
                $users_table[$user->id] = $user->firstname . ' ' . $user->lastname;
            }

            echo json_encode($users_table);
            break;

        case 'received_challenges_problems':
            if(isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $problems = $DB->get_records_sql("SELECT mlp.id, problem_label 
                    FROM mdl_lips_challenge mlc 
                    JOIN mdl_lips_problem mlp ON mlc.challenge_problem = mlp.id
                    AND challenge_to = $userid");

                $problems_table = array();
                foreach ($problems as $problem) {
                    $problems_table[$problem->id] = $problem->problem_label;
                }

                echo json_encode($problems_table);
            }
            break;

        case 'received_challenges_users':
            if(isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $users = $DB->get_records_sql("SELECT mlu.id, firstname, lastname 
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
            if(isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $problems = $DB->get_records_sql("SELECT mlp.id, problem_label 
                    FROM mdl_lips_challenge mlc 
                    JOIN mdl_lips_problem mlp ON mlc.challenge_problem = mlp.id
                    AND challenge_from = $userid");

                $problems_table = array();
                foreach ($problems as $problem) {
                    $problems_table[$problem->id] = $problem->problem_label;
                }

                echo json_encode($problems_table);
            }
            break;

        case 'sent_challenges_users':
            if(isset($_POST['userid'])) {
                $userid = $_POST['userid'];

                $users = $DB->get_records_sql("SELECT mlu.id, firstname, lastname 
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