<?php
define('AJAX_SCRIPT', true);
header('content-type: text/html; charset=utf-8');

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/locallib.php');

global $USER;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'get':
            if (isset($_POST['problemid'])) {
                $userid = get_user_details(array("id_user_moodle" => $USER->id))->id;
                $problemid = $_POST['problemid'];

                $users = fetch_not_challenged_users($userid, $problemid);
                $users_table = array();
                foreach ($users as $user) {
                    $users_table[$user->userid] = ucfirst($user->firstname) . ' ' . $user->lastname;
                }

                echo json_encode($users_table);
            }
            break;

        case 'post':
            if (isset($_POST['lipsid']) && isset($_POST['problemid']) && isset($_POST['users'])) {
                $userid = get_user_details(array("id_user_moodle" => $USER->id))->id;
                $lipsid = $_POST['lipsid'];
                $problemid = $_POST['problemid'];
                $users = $_POST['users'];

                foreach ($users as $to) {
                    if (!is_challenged($userid, $to, $problemid)) {
                        challenge($lipsid, $userid, $to, $problemid);
                    }
                }
            }
            break;
    }
}