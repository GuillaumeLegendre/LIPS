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
        global $USER;
        parent::__construct("mdl_lips_challenges_table");
        $this->cm = $cm;
        $this->owner = $owner;

        $fieldstoselect = "cha.id, problem_label, category_name, difficulty_label, firstname, lastname, challenge_state";
        $tablesfrom = "mdl_lips_problem prob, mdl_lips_category cat, mdl_lips_difficulty diff, mdl_lips_challenge cha, mdl_lips_user mlu_from, mdl_user mu";
		$sql;

        $sql =  "cha.challenge_from = " . $iduser . "
                AND cha.challenge_to = mlu_from.id
                AND mlu_from.id_user_moodle = mu.id
                AND cha.challenge_problem = prob.id
                AND prob.problem_category_id = cat.id
                AND prob.problem_difficulty_id = diff.id";

        if ($search != null) {
        	if (!empty($search->problem) && !empty($search->author)) {
        		$sql = $sql . " AND (problem_label LIKE '%" . $search->problem . "%' AND firstname LIKE '%" . $search->author . "%' OR lastname LIKE '%" . $search->author . "%')";
        	}
        	else if (!empty($search->problem)) {
        		$sql = $sql . " AND (problem_label LIKE '%" . $search->problem . "%')";
        	}
        	else if (!empty($search->author)) {
        		$sql = $sql . " AND (firstname LIKE '%" . $search->author . "%' OR lastname LIKE '%" . $search->author . "%')";
        	}
        }

        $this->set_sql(
            	$fieldstoselect,
            	$tablesfrom,
            	$sql);
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

        $this->define_headers(array(get_string('language', 'lips'), get_string('problem', 'lips'), get_string('category', 'lips'),
            get_string('difficulty', 'lips'), get_string('challenge_challenged', 'lips'), get_string('state', 'lips')));
       
        $this->define_columns(array("language", "problem_label", "category_name", "difficulty", "challenge_author", "state"));

        $this->sortable(true);
    }

    public function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE;

        switch ($colname) {
            case 'language' :
                $challengedetails = get_challenge_details(array('id' => $attempt->id));
                $idlanguage = $challengedetails->challenge_language;
                $moodledetails = get_instance($idlanguage);
                if (!empty($moodledetails->compile_language)) {
                    return ucfirst($moodledetails->compile_language);
                }
                return "";
                break;
        	case 'difficulty':
        		return get_string($attempt->difficulty_label, 'lips');
        		break;
            case 'challenge_author':
                return "$attempt->firstname $attempt->lastname";
                break;
            case 'state':
            	if ($this->owner) {
                	switch ($attempt->challenge_state) {
            			// Problem is in WAITING state.
            			case 'WAITING':
                            // Sent challenges
                            $url_cancel = new action_link(new moodle_url('view.php', array(
                                'id' => $this->cm->id,
                                'view' => 'cancelChallenge',
                                'challengeid' => $attempt->id,
                                'originV' => 'profile',
                                'originAction' => 'challenges'
                            )),
                            get_string('cancel', 'lips'), null, array("class" => "lips-button"));

                            return $OUTPUT->render($url_cancel);
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