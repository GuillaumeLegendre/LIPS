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
class rank_table extends flexible_table {
    private $cm;
    private $rank = 1;
    private $language = null;

    public function  __construct($cm, $searchuser = null, $language = null, $category = null) {
        global $USER, $PAGE, $DB;
        parent::__construct("mdl_lips_category");
        $lipsoutput = $PAGE->get_renderer('mod_lips');
        $this->language = $language;
        $this->cm = $cm;
        $conditions = "1";
        $filterinstance = "";

        if (!empty($searchuser)) {
            $conditions .= " AND mu.firstname like '%" . $searchuser . "%' or mu.lastname like '%" . $searchuser . "%'";
        }
        if (!empty($language)) {
            $conditions .= " AND mls.score_instance =" . $language;
        }
        if (!empty($category)) {
            $conditions .= " AND mlc.id=$category";
        }

        $this->define_baseurl(new moodle_url('view.php', array('id' => $cm->id, 'view' => "rank")));
        $context = context_module::instance($cm->id);

        $this->set_attribute('class', 'admintable generaltable');
        $this->define_headers(array(get_string('rank', 'lips'), get_string('user', 'lips'), get_string("solved_problems", "lips"), get_string("score", "lips"), "Suivre"));
        $this->define_columns(array("rank", "user", "nb_problems_solved", "user_score", "follow"));
        $this->no_sorting("follow");
        $this->sortable(true);
        $this->setup();
        $sortedcolumns = $this->get_sort_columns();
        $sortedcolumn = key($sortedcolumns);

        // SORT.

        $orderby = "order by user_score DESC";
        if ($sortedcolumn == "user") {
            if ($sortedcolumns["user"] == SORT_ASC) {
                $orderby = "order by mu.firstname ASC";
            } else {
                $orderby = "order by mu.firstname DESC";
            }
        } else if ($sortedcolumn == "user_score") {
            if ($sortedcolumns["user_score"] == SORT_ASC) {
                $orderby = "order by user_score ASC";
            } else {
                $orderby = "order by user_score DESC";
            }
        } else if ($sortedcolumn == "rank") {
            if ($sortedcolumns["rank"] == SORT_ASC) {
                $orderby = "order by user_score ASC";
            } else {
                $orderby = "order by user_score DESC";
            }
        }
        $page = optional_param("page", 0, PARAM_INT);
        $limit = $page * 10;

        if (!empty($category)) {

            $totaltuples = $DB->count_records_sql("SELECT COUNT(DISTINCT (problem_solved_user) )
            FROM mdl_lips_user mlu
            JOIN mdl_user mu ON mlu.id_user_moodle = mu.id
            JOIN mdl_lips_problem_solved mlps ON problem_solved_user = mlu.id_user_moodle
            JOIN mdl_lips_problem mlp ON mlps.problem_solved_problem = mlp.id
            WHERE mlp.problem_category_id =$category");

            $sql = "SELECT mlu.id,SUM( difficulty_points ) as user_score, mu.id AS id_moodle_user, mu.firstname, mu.lastname, @curRank := @curRank + 1 AS rank
            FROM (SELECT @curRank := 0) r,mdl_lips_user mlu
            JOIN mdl_user mu ON mlu.id_user_moodle = mu.id
            JOIN mdl_lips_problem_solved mlps ON problem_solved_user = mlu.id_user_moodle
            JOIN mdl_lips_problem mlp ON mlps.problem_solved_problem = mlp.id
            JOIN mdl_lips_difficulty mld ON mld.id = mlp.problem_difficulty_id
            WHERE mlp.problem_category_id =$category
            GROUP BY problem_solved_user
            ORDER BY user_score
            LIMIT $limit , 10";
        } else {

            $totaltuples = $DB->count_records_sql("SELECT count(*)
            FROM `mdl_lips_user` mlu
            JOIN mdl_user mu ON mlu.id_user_moodle = mu.id
            LEFT JOIN mdl_lips_score mls ON mls.score_user=mlu.id
            WHERE $conditions");

            $conditionsselect = str_replace(" AND mu.firstname like '%" . $searchuser . "%' or mu.lastname like '%" . $searchuser . "%'", "", $conditions);

            $sql = "SELECT @rn:=@rn+1 AS rank, id, user_score, id_moodle_user, firstname, lastname
            FROM (
              SELECT mlu.id,SUM( score_score ) as user_score, mu.id AS id_moodle_user, mu.firstname, mu.lastname
              FROM mdl_lips_user mlu
              JOIN mdl_user mu ON mlu.id_user_moodle = mu.id
              LEFT JOIN mdl_lips_score mls ON mls.score_user=mlu.id
              WHERE $conditionsselect
              GROUP BY mlu.id
              ORDER BY user_score
              LIMIT 0 , 10
            ) t1,(SELECT @rn:=0) t2";


           // $sql = "SELECT mlu.id, SUM(score_score) as user_score, mu.id as id_moodle_user, mu.firstname, mu.lastname, @curRank := @curRank + 1 AS rank
            //FROM (SELECT @curRank := 0) r, `mdl_lips_user` mlu
            //JOIN mdl_user mu ON mlu.id_user_moodle = mu.id
            //LEFT JOIN mdl_lips_score mls ON mls.score_user=mlu.id where
            //$conditionsselect GROUP BY mlu.id $orderby LIMIT $limit,10";
        }

        $this->pagesize(10, $totaltuples);

        $res = $DB->get_records_sql($sql);
        $this->no_sorting("nb_problems_solved");

        // Populate the table.

        foreach ($res as $user) {
            if ($user->id) {
                if ($searchuser != null) {
                    if (strpos($user->firstname, $searchuser) === false && strpos($user->lastname, $searchuser) === false) {
                        continue;
                    }
                }
                $sql = "SELECT count(DISTINCT(problem_solved_problem))  FROM mdl_lips_problem_solved WHERE problem_solved_user =$user->id_moodle_user";
                $countproblemsolved = $DB->count_records_sql($sql);

                $userlink = '<div class="user-picture"><img src="' . get_user_picture_url(array('id' => $user->id)) . '"/>' . $lipsoutput->display_user_link($user->id, $user->firstname, $user->lastname) . '</div>';


                $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
                if (is_following($userdetails->id, $user->id)) {
                    $url = new action_link(new moodle_url('action.php', array(
                            'id' => $this->cm->id,
                            'action' => 'unfollow',
                            'originV' => 'rank',
                            'to_unfollow' => $user->id
                        )),
                        get_string('unfollow', 'lips'), null, array("class" => "lips-button"));
                } else {
                    $url = new action_link(new moodle_url('action.php', array(
                            'id' => $this->cm->id,
                            'action' => 'follow',
                            'originV' => 'rank',
                            'to_follow' => $user->id
                        )),
                        get_string('follow', 'lips'), null, array("class" => "lips-button"));
                }
                // You can't follow yourself
                if ($user->id_moodle_user != $USER->id) {
                    $followlink = $lipsoutput->render($url);
                } else {
                    $followlink = '';
                }
                $this->add_data(array($user->rank, $userlink, $countproblemsolved, $user->user_score, $followlink));
            }
        }
    }
}