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

global $CFG;
require_once("$CFG->libdir/tablelib.php");
require_once("$CFG->libdir/outputrenderers.php");

/**
 * Users table
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class users_table extends table_sql {
    private $cm;

    /**
     * users_table constructor
     *
     * @param object $cm Moodle context
     * @param string $search User to search
     */
    public function  __construct($cm, $search = null) {
        parent::__construct("mdl_lips_user");
        $this->cm = $cm;

        if($search == null) {
            $this->set_sql("mlu.id, mlu.id_user_moodle, firstname, lastname, user_status, rank_label",
                "mdl_lips_user mlu, mdl_user mu, mdl_lips_rank mlr",
                "mlu.id_user_moodle = mu.id AND mlu.user_rank_id = mlr.id");
            $this->set_count_sql('SELECT COUNT(*) FROM mdl_lips_user');
        } else {
            $this->set_sql("mlu.id, mlu.id_user_moodle, firstname, lastname, user_status, rank_label",
                "mdl_lips_user mlu, mdl_user mu, mdl_lips_rank mlr",
                "mlu.id_user_moodle = mu.id AND mlu.user_rank_id = mlr.id AND (firstname LIKE '%" . $search . "%' OR lastname LIKE '%" . $search . "%')");
            $this->set_count_sql("SELECT COUNT(*) FROM mdl_lips_user mlu, mdl_user mu WHERE mlu.id_user_moodle = mu.id AND (firstname LIKE '%" . $search . "%' OR lastname LIKE '%" . $search . "%')");
        }
        $this->define_baseurl(new moodle_url('view.php', array('id' => $cm->id, 'view' => "users")));
        $context = context_module::instance($cm->id);

        $this->define_headers(array(get_string('user', 'lips'), get_string('status', 'lips'), get_string('grade', 'lips'), ''));
        $this->define_columns(array("user_name", "user_status", "rank_label", "user_follow"));
        $this->sortable(true);
    }

    /**
     * Other columns of the table
     *
     * @param string $colname Column name
     */
    public function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE, $USER;

        switch($colname) {
            case 'user_name':
                $url = new action_link(new moodle_url('view.php', array(
                    'id' => $this->cm->id,
                    'view' => 'profile',
                    'id_user' => $attempt->id)),
                ucfirst($attempt->firstname) . ' ' . strtoupper($attempt->lastname));

                return '<div class="user-picture"><img src="' . get_user_picture_url(array('id' => $attempt->id)) . '"/>' . $OUTPUT->render($url) . '</div>';
                break;

            case 'user_status':
                return get_string($attempt->user_status, 'lips');
                break;

            case 'user_follow':
                $userdetails = get_user_details(array('id_user_moodle' => $USER->id));

                if(is_following($userdetails->id, $attempt->id)) {
                    $url = new action_link(new moodle_url('action.php', array(
                        'id' => $this->cm->id,
                        'action' => 'unfollow',
                        'originV' => 'users',
                        'to_unfollow' => $attempt->id
                    )),
                    get_string('unfollow', 'lips'), null, array("class" => "lips-button"));
                } else {
                    $url = new action_link(new moodle_url('action.php', array(
                        'id' => $this->cm->id,
                        'action' => 'follow',
                        'originV' => 'users',
                        'to_follow' => $attempt->id
                    )),
                    get_string('follow', 'lips'), null, array("class" => "lips-button"));
                }

                // You can't follow yourself
                if($attempt->id_user_moodle != $USER->id) {
                    return $OUTPUT->render($url);
                } else {
                    return '';
                }
                break;
        }

        return null;
    }
} 