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
 * Rank table
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rank_table extends table_sql {
    private $cm;
    private $rank = 1;

    public function  __construct($cm, $searchuser = null, $language = null, $category = null) {
        global $USER;
        parent::__construct("mdl_lips_category");
        $this->cm = $cm;
        $conditions = "1";
        if (!empty($searchuser)) {
            $conditions .= " AND mu.firstname like '%" . $searchuser . "%' or mu.lastname like '%" . $searchuser . "%'";
        }
        if (!empty($language)) {
            $conditions .= " AND mlur.user_rights_instance=$language AND mlc.id_language=$language";
        }
        if (!empty($category)) {
            $conditions .= " AND mlc.id=$category";
        }
        $this->set_sql("mlu.id, SUM(score_score) as user_score, mu.id as id_moodle_user, mu.firstname, mu.lastname,
        COUNT( DISTINCT(mlps.problem_solved_problem) ) AS nb_problems_solved",
            " `mdl_lips_user` mlu JOIN mdl_user mu ON mlu.id_user_moodle = mu.id
            left JOIN mdl_lips_problem_solved mlps on mlps.problem_solved_user=mlu.id_user_moodle
            left JOIN mdl_lips_score mls ON mls.score_user=mlu.id
            left JOIN mdl_lips_problem mlp ON mlp.id=mlps.problem_solved_problem
            left JOIN mdl_lips_category mlc ON mlc.id=mlp.problem_category_id
            left JOIN mdl_lips_user_rights mlur ON user_rights_user=mlu.id",
            $conditions . " GROUP BY  mlu.id_user_moodle order by user_score DESC");

        $this->set_count_sql("SELECT count(*) FROM mdl_lips_user  mlu
            JOIN mdl_user mu ON mlu.id_user_moodle = mu.id
            left JOIN mdl_lips_problem_solved mlps on mlps.problem_solved_user=mlu.id_user_moodle
            left JOIN mdl_lips_score mls ON mls.score_user=mlu.id
            left JOIN mdl_lips_problem mlp ON mlp.id=mlps.problem_solved_problem
            left JOIN mdl_lips_category mlc ON mlc.id=mlp.problem_category_id
            left JOIN mdl_lips_user_rights mlur ON user_rights_user=mlu.id
            where $conditions");
        $this->define_baseurl(new moodle_url('view.php', array('id' => $cm->id, 'view' => "problems")));
        $context = context_module::instance($cm->id);

        $this->define_headers(array(get_string('rank', 'lips'), get_string('user', 'lips'), get_string("solved_problems", "lips"), "score", ""));
        $this->define_columns(array("rank", "user", "nb_problems_solved", "user_score", "suivre"));

        $this->sortable(true);
    }

    public function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE, $USER;
        $lipsoutput = $PAGE->get_renderer('mod_lips');

        if ($colname == "rank") {
            return $this->rank++;
        }

        if ($colname == "user") {
            return $lipsoutput->display_user_link($attempt->id, $attempt->firstname, $attempt->lastname);
        }
        if ($colname == "user_score") {
            if (empty($attempt->user_score)) {
                return 0;
            }
        }

        if ($colname == 'suivre') {
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
            if (is_following($userdetails->id, $attempt->id)) {
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
            if ($attempt->id_moodle_user != $USER->id) {
                return $OUTPUT->render($url);
            } else {
                return '';
            }
        }

        return null;
    }
} 