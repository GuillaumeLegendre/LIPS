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

global $CFG;

require_once("$CFG->libdir/tablelib.php");
require_once("$CFG->libdir/outputrenderers.php");


class problems_table extends table_sql {
    private $cm;

    /**
     * Override the get_sql_sort function to add a default sort
     */
    function get_sql_sort() {
        $order = parent::construct_order_by($this->get_sort_columns());

        $defaultorder = array(
            'problem_label' => 'ASC',
            'difficulty_points' => 'ASC'
        );

        if ($order == '') {
            foreach ($defaultorder as $key => $value) {
                if (strpos($order, $key) === false) {
                    $order = "$key $value, $order";
                }
            }
        }

        return trim($order, ", ");
    }

    function  __construct($cm, $id, $search = null) {
        global $USER;
        parent::__construct("mdl_lips_problem");
        $this->cm = $cm;

        if ($search != null) {
            $this->set_sql("mlp.id, problem_label, problem_category_id, problem_date, problem_creator_id, difficulty_label, difficulty_points, count(mls.id) AS problem_resolutions, firstname, lastname, mlu.id AS user_id, problem_testing, mlu.id_user_moodle",
                "mdl_lips_problem mlp JOIN mdl_lips_difficulty mld ON problem_difficulty_id = mld.id 
                LEFT JOIN mdl_lips_problem_solved mls ON mls.problem_solved_problem = mlp.id 
                JOIN mdl_user mu ON mu.id = problem_creator_id 
                JOIN mdl_lips_user mlu ON mlu.id_user_moodle = problem_creator_id",
                "problem_category_id = " . $id . " 
                AND problem_label LIKE '%$search%' 
                AND (problem_testing = 0 OR problem_testing = 1 AND problem_creator_id = " . $USER->id . ") 
                GROUP BY mlp.id");
        } else {
            $this->set_sql("mlp.id, problem_label, problem_category_id, problem_date, problem_creator_id, difficulty_label, count(mls.id) AS problem_resolutions, firstname, lastname, mlu.id AS user_id, problem_testing,  mlu.id_user_moodle",
                "mdl_lips_problem mlp JOIN mdl_lips_difficulty mld ON problem_difficulty_id = mld.id 
                LEFT JOIN mdl_lips_problem_solved mls ON mls.problem_solved_problem = mlp.id 
                JOIN mdl_user mu ON mu.id = problem_creator_id 
                JOIN mdl_lips_user mlu ON mlu.id_user_moodle = problem_creator_id",
                "problem_category_id = " . $id . " 
                AND (problem_testing = 0 OR problem_testing = 1 AND problem_creator_id = " . $USER->id . ") 
                GROUP BY mlp.id");
        }
        $this->define_baseurl(new moodle_url('view.php', array('id' => $cm->id, 'view' => 'category', "categoryId" => $id)));
        $this->set_count_sql("SELECT COUNT(*) FROM mdl_lips_problem where problem_category_id = " . $id);
        $context = context_module::instance($cm->id);
        if (has_capability('mod/lips:administration', $context)) {
            $this->define_headers(array("Problème",
                "Difficulté",
                "Date",
                "Rédacteur",
                "Nombre de résolutions",
                "Résolu",
                ""));
            $this->define_columns(array(
                "problem_label",
                "difficulty_label",
                "problem_date",
                "firstname",
                "problem_resolutions",
                "solved",
                "actions"));
        } else {
            $this->define_headers(array("Problème",
                "Difficulté",
                "Date",
                "Rédacteur",
                "Nombre de résolutions",
                "Résolu"));
            $this->define_columns(array("problem_label",
                "difficulty_label",
                "problem_date",
                "firstname",
                "problem_resolutions",
                "solved"));
        }
        $this->no_sorting("solved");
        $this->no_sorting("actions");
        $this->sortable(true);
    }

    function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE, $USER;
        $lipsoutput = $PAGE->get_renderer('mod_lips');

        switch ($colname) {
            case 'problem_label':
                $star = ($USER->id == $attempt->problem_creator_id) ? ' <span style="color :red">*</span>' : '';
                $img = ($attempt->problem_testing == 1) ?
                    '<img src="images/' . get_string('picture_testing', 'lips') . '" class="testing-picture"/>' : '';

                $url = new action_link(new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => 'problem', 'problemId' => $attempt->id)), $attempt->problem_label);
                return $OUTPUT->render($url) . $star . $img;
                break;

            case 'problem_date':
                return date('d/m/Y', $attempt->problem_date);
                break;

            case 'difficulty_label':
                return get_string($attempt->difficulty_label, "lips");
                break;

            case 'solved':
                if (has_solved_problem($attempt->id, $USER->id)) {
                    return '<img src="images/' . get_string('notification_problem_solved_picture', 'lips') . '"/>';
                } else {
                    return '';
                }
                break;

            case 'actions':
                $context = context_module::instance($this->cm->id);
                $a = "";

                if (has_capability('mod/lips:administration', $context) && is_author($attempt->id, $USER->id)) {
                    $a = $OUTPUT->action_icon(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'problem_modify', 'problemId' => $attempt->id)), new pix_icon("t/edit", "edit"));
                    $a .= " " . $OUTPUT->action_icon(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'deleteProblem', 'problemId' => $attempt->id, 'originV' => "category", "categoryId" => $attempt->problem_category_id)), new pix_icon("t/delete", "delete"));
                }

                return $a;
                break;

            case 'firstname':
                return $lipsoutput->display_user_link($attempt->user_id, $attempt->firstname, $attempt->lastname);
                break;
        }

        return null;
    }
}