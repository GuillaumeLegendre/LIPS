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
 * Solved problems table
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class solved_problems_table extends table_sql {
    private $cm;
    private $userid;
    private $owner;

    /**
     * Override the get_sql_sort function to add a default sort
     */
    function get_sql_sort() {
        $order = parent::construct_order_by($this->get_sort_columns());

        $defaultorder = array(
            'compile_language' => 'ASC',
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
     * solved_problems_table constructor
     *
     * @param object $cm Moodle context
     * @param string $search Search value
     * @param int $userid User ID (Moodle)
     * @param bool True if the current user if the profile owner, otherwise false
     */
    function  __construct($cm, $search = null, $userid, $owner) {
        parent::__construct("mdl_lips_problem");
        $this->cm = $cm;
        $this->userid = $userid;
        $this->owner = $owner;

        if ($search == null) {
            $this->set_sql('t.*', '(
                (
                    SELECT mlp.id AS problem_id, ml.id AS language_id, ml.compile_language, mlp.problem_label, difficulty_label, difficulty_points, problem_solved_date AS problem_date, "solved" AS state
                    FROM mdl_lips_problem_solved mls
                    JOIN mdl_lips_problem mlp ON mlp.id = mls.problem_solved_problem 
                    JOIN mdl_lips_category mlc ON mlc.id = mlp.problem_category_id
                    JOIN mdl_lips ml ON mlc.id_language = ml.id
                    JOIN mdl_lips_difficulty mld ON mlp.problem_difficulty_id = mld.id
                    WHERE problem_solved_user = ' . $userid . '
                    AND problem_testing = 0
                )
                UNION ALL
                (
                    SELECT mlp.id AS problem_id, ml.id AS language_id, ml.compile_language, mlp.problem_label, difficulty_label, difficulty_points, problem_failed_date AS problem_date, "failed" AS state
                    FROM mdl_lips_problem_failed mls
                    JOIN mdl_lips_problem mlp ON mlp.id = mls.problem_failed_problem 
                    JOIN mdl_lips_category mlc ON mlc.id = mlp.problem_category_id
                    JOIN mdl_lips ml ON mlc.id_language = ml.id
                    JOIN mdl_lips_difficulty mld ON mlp.problem_difficulty_id = mld.id
                    WHERE problem_failed_user = ' . $userid . '
                    AND problem_testing = 0
                )) t', '1 GROUP BY problem_id');
        } else {
            /*$this->set_sql("mlp.id AS problem_id, ml.id AS language_id, compile_language, problem_label,
                difficulty_label, difficulty_points, problem_date",
                "mdl_lips_problem_solved mlps
                JOIN mdl_lips_problem mlp ON mlps.problem_solved_problem = mlp.id
                JOIN mdl_lips_difficulty mld ON problem_difficulty_id = mld.id
                JOIN mdl_lips_category mlc ON mlc.id = mlp.problem_category_id
                JOIN mdl_lips ml ON mlc.id_language = ml.id",
                "mlps.problem_solved_user = $userid
                AND problem_testing = 0
                AND problem_label LIKE '%" . $search . "%'
                GROUP BY mlp.id");*/
        }
        $this->set_count_sql("SELECT count(DISTINCT problem_solved_problem)
            FROM mdl_lips_problem_solved
            WHERE problem_solved_user = $userid");

        $this->define_baseurl(new moodle_url('view.php',
            array('id' => $cm->id, 'view' => 'profile', 'action' => 'solved_problems')));

        $this->define_headers(array(
            get_string('language', 'lips'),
            get_string('problem', 'lips'),
            get_string('difficulty', 'lips'),
            get_string('date', 'lips'),
            get_string('state', 'lips'),
            ""
        ));
        $this->define_columns(array(
            "compile_language",
            "problem_label",
            "difficulty_points",
            "problem_date",
            "state",
            "solution"
        ));
        $this->sortable(true);
        $this->no_sorting("solution");
    }

    /**
     * Other columns of the table
     *
     * @param string $colname Column name
     * @param int $attempt Data
     */
    function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE, $USER;

        switch ($colname) {
            case 'compile_language':
                $instance = get_instance($attempt->language_id);
                $url = new action_link(new moodle_url('view.php', array(
                    'id' => $instance->instance_link)
                ), ucfirst($attempt->compile_language));
                return $OUTPUT->render($url);
                break;

            case 'problem_label':
                $instance = get_instance($attempt->language_id);
                $url = new action_link(new moodle_url('view.php', array(
                    'id' => $instance->instance_link,
                    'view' => 'problem',
                    'problemId' => $attempt->problem_id)
                ), $attempt->problem_label);
                return $OUTPUT->render($url);
                break;

            case 'difficulty_points':
                return get_string($attempt->difficulty_label, "lips");
                break;

            case 'problem_date':
                return date('d/m/Y', $attempt->problem_date);
                break;

            case 'state':
                switch($attempt->state) {
                    case 'solved':
                    return '<img src="images/' . get_string('notification_problem_solved_picture', 'lips') . '"/>';
                    break;

                    case 'failed':
                    return '<img src="images/' . get_string('picture_failed', 'lips') . '"/>';
                    break;
                }
                break;

            case 'solution':
                if ($this->owner ||
                    nb_resolutions_problem($USER->id, $attempt->problem_id) > 0 ||
                    is_author($attempt->problem_id, $USER->id)
                ) {
                    $instance = get_instance($attempt->language_id);
                    $url = new action_link(new moodle_url('view.php', array(
                            'id' => $instance->instance_link,
                            'view' => 'solutions',
                            'problemId' => $attempt->problem_id,
                            'userid' => $this->userid
                        )), get_string('answers', 'lips'), null, array("class" => "lips-button"));

                    return $OUTPUT->render($url);
                } else {
                    return '';
                }
                break;
        }

        return null;
    }
}