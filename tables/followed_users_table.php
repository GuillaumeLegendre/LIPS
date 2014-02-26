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
 * Followed users table
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class followed_users_table extends table_sql {
    private $cm;

    /**
     * followed_users_table constructor
     *
     * @param object $cm Moodle context
     * @param int $iduser User ID
     * @param bool $owner True if the user is the owner of the profile, otherwise false
     * @param string $search User to search
     */
    public function  __construct($cm, $iduser, $owner, $search = null) {
        parent::__construct("mdl_lips_category");
        $this->cm = $cm;

        if ($search == null) {
            $this->set_sql("mlu.id, firstname, lastname, rank_label",
                "mdl_lips_follow mlf, mdl_lips_user mlu, mdl_lips_rank mlr, mdl_user mu",
                "mlf.followed = mlu.id
                AND mlu.user_rank_id = mlr.id
                AND mu.id = mlu.id_user_moodle
                AND mlf.follower = " . $iduser);
        } else {
            $this->set_sql("mlu.id, firstname, lastname, rank_label",
                "mdl_lips_follow mlf, mdl_lips_user mlu, mdl_lips_rank mlr, mdl_user mu",
                "mlf.followed = mlu.id
                AND mlu.user_rank_id = mlr.id
                AND mu.id = mlu.id_user_moodle
                AND mlf.follower = " . $iduser . "
                AND (firstname LIKE '%" . $search . "%' OR lastname LIKE '%" . $search . "%')");
        }
        $this->set_count_sql("SELECT COUNT(*) FROM mdl_lips_follow WHERE follower = " . $iduser);

        if ($owner) {
            $this->define_baseurl(new moodle_url('view.php',
                array('id' => $cm->id, 'view' => 'profile', 'action' => 'followed_users')));
            $this->define_headers(array(get_string('user', 'lips'), get_string('grade', 'lips'), ''));
            $this->define_columns(array("user_infos", "rank_label", "user_follow"));
        } else {
            $this->define_baseurl(new moodle_url('view.php',
                array('id' => $cm->id, 'view' => 'profile', 'action' => 'followed_users', 'id_user' => $iduser)));
            $this->define_headers(array(get_string('user', 'lips'), get_string('grade', 'lips')));
            $this->define_columns(array("user_infos", "rank_label"));
        }

        $this->sortable(true);
    }

    public function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE;

        switch ($colname) {
            case 'user_infos':
                $url = new action_link(new moodle_url('view.php', array(
                        'id' => $this->cm->id,
                        'view' => 'profile',
                        'id_user' => $attempt->id)),
                    ucfirst($attempt->firstname) . ' ' . strtoupper($attempt->lastname));

                return '<div class="user-picture"><img src="' . get_user_picture_url(array('id' => $attempt->id)) . '"/>'
                . $OUTPUT->render($url) . '</div>';
                break;

            case 'user_follow':
                $url = new action_link(new moodle_url('action.php', array(
                        'id' => $this->cm->id,
                        'action' => 'unfollow',
                        'originV' => 'profile',
                        'originAction' => 'followed_users',
                        'to_unfollow' => $attempt->id
                    )),
                    get_string('unfollow', 'lips'), null, array("class" => "lips-button"));

                return $OUTPUT->render($url);
                break;
        }

        return null;
    }
} 