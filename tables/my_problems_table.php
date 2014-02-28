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

/**
 * Teach problems table
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class my_problems_table extends table_sql {
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

        return trim($order, ', ');
    }

    /**
     * my_problems_table constructor
     *
     * @param object $cm Moodle context
     * @param int $idlanguage Language ID
     * @param int $idcreator Create ID
     */
    function  __construct($cm, $idlanguage, $idcreator) {
        parent::__construct("mdl_lips_problem");
        $this->cm = $cm;

        $this->set_sql("mlp.id, problem_label, problem_date, problem_creator_id, difficulty_label, difficulty_points, count(mls.id) as problem_resolutions, firstname, lastname, mlu.id AS user_id, problem_testing",
            "mdl_lips_problem mlp JOIN mdl_lips_difficulty mld ON problem_difficulty_id = mld.id 
            LEFT JOIN mdl_lips_problem_solved mls ON mls.problem_solved_problem = mlp.id 
            JOIN mdl_user mu ON mu.id = problem_creator_id 
            JOIN mdl_lips_user mlu ON mlu.id_user_moodle = problem_creator_id 
            JOIN mdl_lips_category mlc ON mlc.id = mlp.problem_category_id",
            "mlc.id_language = " . $idlanguage . " AND mlp.problem_creator_id = " . $idcreator . " 
            GROUP BY mlp.id");
        $this->set_count_sql("SELECT COUNT(*) FROM mdl_lips_problem WHERE problem_creator_id = " . $idcreator);
        $this->define_baseurl(new moodle_url('view.php', array('id' => $cm->id, 'view' => 'administration', 'action' => 'my_problems')));

        $this->define_headers(array(
            get_string('problem', 'lips'),
            get_string('difficulty', 'lips'),
            get_string('date', 'lips'),
            get_string('problem_nb_resolutions', 'lips'),
            "",
            ""));
        $this->define_columns(array(
            "problem_label",
            "difficulty_points",
            "problem_date",
            "problem_resolutions",
            "testing",
            "actions"));
        $this->sortable(true, 2);
        $this->no_sorting("testing");
        $this->no_sorting("actions");
    }

    /**
     * Other columns of the table
     *
     * @param string $colname Column name
     * @param int $attempt Data
     */
    function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE;

        switch ($colname) {
            case 'problem_label':
                $url = new action_link(new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => 'problem', 'problemId' => $attempt->id)), $attempt->problem_label);
                return $OUTPUT->render($url);
                break;

            case 'problem_date':
                return date('d/m/Y', $attempt->problem_date);
                break;

            case 'difficulty_points':
                return get_string($attempt->difficulty_label, "lips");
                break;

            case 'testing':
                if ($attempt->problem_testing == 1) {
                    $url = new action_link(new moodle_url('action.php', array(
                            'id' => $this->cm->id,
                            'action' => 'untesting',
                            'originV' => 'administration',
                            'originAction' => 'my_problems',
                            'to_untest' => $attempt->id
                        )),
                        get_string('untesting', 'lips'), null, array("class" => "lips-button"));
                } else {
                    $url = new action_link(new moodle_url('action.php', array(
                            'id' => $this->cm->id,
                            'action' => 'testing',
                            'originV' => 'administration',
                            'originAction' => 'my_problems',
                            'to_test' => $attempt->id
                        )),
                        get_string('testing', 'lips'), null, array("class" => "lips-button"));
                }

                return $OUTPUT->render($url);
                break;

            case 'actions':
                $actions = $OUTPUT->action_icon(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'problem_modify', "problemId" => $attempt->id)), new
                pix_icon("t/edit", "edit"));
                $actions .= " " . $OUTPUT->action_icon(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'deleteProblem', "problemId" => $attempt->id, 'originV' => 'administration',
                        'originAction' => 'my_problems')), new pix_icon("t/delete", "delete"));

                return $actions;
                break;
        }

        return null;
    }
}