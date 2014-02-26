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
 * @package   mod_lips
 * @copyright 2014 LIPS
 *
 * @author Valentin Got
 * @author Guillaume Legendre
 * @author Mickael Ohlen
 * @author Anaïs Picoreau
 * @author Julien Senac
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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