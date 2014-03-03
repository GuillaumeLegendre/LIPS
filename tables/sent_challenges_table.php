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
 * Sent challenges table
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sent_challenges_table extends table_sql {

    private $owner = false;

    /**
     * sent_challenges_table constructor
     *
     * @param object $cm Moodle context
     * @param int $iduser User ID
     * @param bool $owner True if the user is the owner of the profile, otherwise false
     * @param string $search User to search
     */
    public function  __construct($cm, $iduser, $owner, $search = null) {
        parent::__construct("mdl_lips_challenges_table");
        $this->cm = $cm;
        $this->owner = $owner;

        $fieldstoselect = "cha.id, challenge_problem, problem_label, problem_category_id, category_name, difficulty_label, difficulty_points,
        challenge_to, firstname, lastname, challenge_state, compile_language";
        $tablesfrom = "mdl_lips_challenge cha
            JOIN mdl_lips_user mlu_from ON cha.challenge_to = mlu_from.id
            JOIN mdl_user mu ON mlu_from.id_user_moodle = mu.id
            JOIN mdl_lips_problem prob ON cha.challenge_problem = prob.id
            JOIN mdl_lips_category cat ON prob.problem_category_id = cat.id
            JOIN mdl_lips_difficulty diff ON prob.problem_difficulty_id = diff.id
            LEFT JOIN mdl_lips lips ON lips.id = cat.id_language";
        $where = "cha.challenge_from = " . $iduser;

        if ($search != null) {
            if (!empty($search->problem) && !empty($search->author)) {
                $where = $where . " AND (problem_label LIKE '%" . $search->problem . "%' AND
                 CONCAT(firstname, ' ', lastname) LIKE '%" . $search->author . "%')";
            } else {
                if (!empty($search->problem)) {
                    $where = $where . " AND (problem_label LIKE '%" . $search->problem . "%')";
                } else {
                    if (!empty($search->author)) {
                        $where = $where . " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $search->author . "%'";
                    }
                }
            }
        }

        $this->set_sql(
            $fieldstoselect,
            $tablesfrom,
            $where);
        $this->set_count_sql("
        	SELECT COUNT(*)
        	FROM mdl_lips_challenge cha
        	WHERE cha.challenge_from = " . $iduser);

        if ($owner) {
            $this->define_baseurl(new moodle_url('view.php',
                array('id' => $cm->id, 'view' => 'profile', 'action' => 'challenges')));
        } else {
            $this->define_baseurl(new moodle_url('view.php',
                array('id' => $cm->id, 'view' => 'profile', 'action' => 'challenges', 'id_user' => $iduser)));
        }

        $this->define_headers(array(
            get_string('language', 'lips'),
            get_string('problem', 'lips'),
            get_string('category', 'lips'),
            get_string('difficulty', 'lips'),
            get_string('challenge_challenged', 'lips'),
            get_string('state', 'lips')));

        $this->define_columns(array(
            "compile_language",
            "problem_label",
            "category_name",
            "difficulty_points",
            "firstname", "state"));

        $this->sortable(true);
        $this->no_sorting("state");
    }

    public function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE;
        $lipsoutput = $PAGE->get_renderer('mod_lips');

        switch ($colname) {
            case 'compile_language' :
                break;
            case 'problem_label' :
                $url = new action_link(new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => 'problem', 'problemId' => $attempt->challenge_problem)), $attempt->problem_label);
                return $OUTPUT->render($url);
                break;
            case 'category_name' :
                $url = new action_link(new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => 'category', 'categoryId' => $attempt->problem_category_id)),
                $attempt->category_name);
            return $OUTPUT->render($url);
                break;
            case 'difficulty_points':
                return get_string($attempt->difficulty_label, 'lips');
                break;
            case 'firstname':
                return $lipsoutput->display_user_link($attempt->challenge_to, $attempt->firstname, $attempt->lastname);
                break;
            case 'state':
                if ($this->owner) {
                    switch ($attempt->challenge_state) {
                        // Problem is in WAITING state.
                        case 'WAITING':
                            // Sent challenges.
                            $urlcancel = new action_link(
                                new moodle_url('view.php', array(
                                    'id' => $this->cm->id,
                                    'view' => 'cancelChallenge',
                                    'challengeid' => $attempt->id,
                                    'originV' => 'profile',
                                    'originAction' => 'challenges'
                                )),
                                get_string('cancel', 'lips'), null, array("class" => "lips-button"));

                            return $OUTPUT->render($urlcancel);
                            break;

                        // Problem is in ACCEPTED state.
                        case 'ACCEPTED':

                            // Problem is in SOLVED state.
                        case 'SOLVED':

                            // Problem is in REFUSED state.
                        case 'REFUSED':
                            return get_string($attempt->challenge_state, 'lips');
                            break;
                    }
                } else {
                    return get_string($attempt->challenge_state, 'lips');
                }
                break;
        }
        return null;
    }
}