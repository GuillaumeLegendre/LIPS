<?php

require_once(dirname(__FILE__) . '/page_view.php');

/**
 * Display a problem.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_problem extends page_view {
    private $id;

    function  __construct($cm, $id) {
        parent::__construct($cm, "problem");

        $this->id = $id;
    }

    /**
     * Display the view
     */
    function display() {

        // Manage rights
        if (problem_exists_for_conditions(array('id' => $this->id))) {
            $details = get_problem_details($this->id);
            $categorydetails = get_category_details($details[$this->id]->problem_category_id);
            $lipsinstance = get_instance($categorydetails->id_language);
            if ($lipsinstance->instance_link != $this->cm->id) {
                redirect(new moodle_url('view.php', array('id' => $lipsinstance->instance_link, 'view' => 'problem', 'problemId' => $this->id)));
            }
        } else {
            redirect(new moodle_url('view.php', array('id' => $this->cm->id)));
        }

        parent::display_header();
        $this->display_content();
        parent::display_footer();
    }

    function display_content() {
        global $USER;

        require_once(dirname(__FILE__) . '/../form/mod_lips_search_form.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_problem_form.php');
        require_once(dirname(__FILE__) . '/../lips_rest_interface_ideone.php');

        // Problem details
        $lips = get_current_instance();
        $details = get_problem_details($this->id);
        $categorydetails = get_category_details($details[$this->id]->problem_category_id);
        $difficultydetails = get_difficulty_details(array('difficulty_label' => $details[$this->id]->difficulty_label));

        $notifanswer = "";
        $formanswer = new mod_lips_problems_resolve_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'problemId' => $this->id)), array('idproblem' => $this->id), 'post', '', array('class' => 'solve-button'));
        if ($formanswer->is_submitted()) {
            $formanswer = new mod_lips_problems_resolve_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'problemId' => $this->id)), null, 'post', '', array('class' => 'solve-button'));
            increment_attempt($this->id);
            $data = $formanswer->get_data();
            $codeinformations = get_code_complete($this->id, $data->problem_answer);
            $languages = lips_rest_interface_ideone::execute($codeinformations['code'], get_current_instance()->compile_language);
            if (!$languages) {
                $notifanswer = $this->lipsoutput->display_notification(get_string("web_service_compil_communication_error", "lips"), 'ERROR');
            } else if ($languages['result'] != 1) {
                $notifanswer = $this->lipsoutput->display_notification(nl2br($languages['error']), 'ERROR');
            } else if (trim($languages['output']) == $codeinformations['idtrue']) {
                insert_solution($data->problem_answer, $this->id, $USER->id, $details[$this->id]->problem_category_id);
                $notifanswer = $this->lipsoutput->display_notification(get_string("problem_solved_success", "lips") . '<span class="success-solve">+ ' . $difficultydetails->difficulty_points . ' pt(s)</span>', 'SUCCESS');
            } else {
                $notifanswer = $this->lipsoutput->display_notification(get_string("problem_solved_fail", "lips"), 'ERROR');
            }
        }

        // Update details after post a solution.
        $details = get_problem_details($this->id);

        // Redirect if not allowed to see this problem
        if ($details[$this->id]->problem_testing == 1 && $USER->id != $details[$this->id]->problem_creator_id) {
            redirect(new moodle_url('view.php', array('id' => $this->cm->id)));
        }

        if ($details[$this->id]->problem_testing == 1) {
            echo $this->lipsoutput->display_notification(get_string('problem_testing_info', 'lips'), 'INFO');
        }

        /*--------------------------------
         *   Right buttons
         *------------------------------*/

        // Challenge button
        $buttondefie = $this->lipsoutput->action_link(new moodle_url("#"), get_string('challenge', 'lips'), null, array("class" => "lips-button", "id" => "challenge"));

        // Solutions button
        $buttonsolutions = "";
        if (nb_resolutions_problem($USER->id, $this->id) > 0 || is_author($this->id, $USER->id)) {
            $buttonsolutions = $this->lipsoutput->action_link(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => $this->view, 'view' => 'solutions', "problemId" => $this->id)), get_string('solutions', 'lips'), null, array("class" => "lips-button"));
        }

        // Modify & Delete button
        $buttonedit = "";
        $buttondelete = "";
        if (has_role("administration") && is_author($this->id, $USER->id)) {
            $buttonedit = $this->lipsoutput->action_link(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'problem_modify', 'problemId' => $this->id)), get_string("edit", "lips"), null, array("class" => "lips-button"));
            $buttondelete = $this->lipsoutput->action_link(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => 'deleteProblem', 'problemId' => $this->id, 'originV' => 'problem')), get_string("delete", "lips"), null, array("class" => "lips-button"));
        }

        /*--------------------------------
         *   Left informations
         *------------------------------*/

        // Category documentation
        echo $this->lipsoutput->display_documentation($categorydetails);

        // Problem title
        echo $this->lipsoutput->display_h2($details[$this->id]->problem_label);

        // Buttons
        echo $this->lipsoutput->display_div($buttondefie . $buttonsolutions . $buttonedit . $buttondelete, array("id" => "problem-right-buttons"));

        // Author
        $authorlink = $this->lipsoutput->action_link(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'profile', 'id_user' => $details[$this->id]->user_id)), ucfirst($details[$this->id]->firstname) . ' ' . ucfirst($details[$this->id]->lastname));
        echo $this->lipsoutput->display_problem_information(get_string("problem_author", "lips"), $authorlink);

        // Creation date
        echo $this->lipsoutput->display_problem_information(get_string("problem_date_creation", "lips"), format_date($details[$this->id]->problem_date, false));

        // Number of resolutions
        echo $this->lipsoutput->display_problem_information(get_string("problem_nb_resolutions", "lips"), $details[$this->id]->problem_resolutions . " / " . $details[$this->id]->problem_attempts . " " . get_string("attempts", "lips"));

        // Difficulty
        echo $this->lipsoutput->display_problem_information(get_string("difficulty", "lips"), get_string($details[$this->id]->difficulty_label, "lips"));

        // Prerequisite
        $prerequisite = $details[$this->id]->problem_preconditions;
        if (empty($prerequisite)) {
            $prerequisite = get_string("none", "lips");
        }
        echo $this->lipsoutput->display_problem_information(get_string("prerequisite", "lips"), $prerequisite);

        /*--------------------------------
         *   Core informations
         *------------------------------*/

        // Subject
        echo $this->lipsoutput->display_h3(get_string("subject", "lips"), array("style" => "margin-bottom: 10px;"), false);
        echo $this->lipsoutput->display_p($details[$this->id]->problem_statement);

        // Tips
        if (!empty($details[$this->id]->problem_tips)) {
            echo $this->lipsoutput->display_h3(get_string("tips", "lips"), array("style" => "margin-bottom: 10px;"), false);
            echo $this->lipsoutput->display_p($details[$this->id]->problem_tips);
        }

        // Unit tests
        $hastest = false;
        $unittests = get_displayable_unittests($details[$this->id]->problem_unit_tests);
        if (count($unittests[1]) > 0) {
            echo $this->lipsoutput->display_h3(get_string("administration_problem_create_code_unittest_label", "lips"), array("style" => "margin-bottom: 10px;"), false);

            foreach ($unittests[1] as $unittest) {
                $img = $this->lipsoutput->display_img(get_unitest_picture());
                echo $this->lipsoutput->display_p($img . $this->lipsoutput->display_span($unittest), array('class' => 'unit-test'));
                $hastest = true;
            }
        }

        // Answer
        echo $this->lipsoutput->display_h3(get_string("answer", "lips"), array("style" => "margin-bottom: 10px;"), false);
        // echo '<div id="answerEditor" class="ace">' . htmlspecialchars($details[$this->id]->problem_code) . '</div>';

        // echo '<br/><center><a href="#" class="lips-button">' . get_string('send_response', 'lips') . '</a></center>';

        echo $notifanswer;

        $formanswer->display();

        // Similar problems
        $similarproblems = get_similar_problems($this->id);
        if (count($similarproblems) > 0) {
            echo $this->lipsoutput->display_h3(get_string("similar_problems", "lips"), array("style" => "margin-bottom: 10px; margin-top: 20px"), false);

            foreach ($similarproblems as $similarproblem) {
                $problemdetails = get_problem_details($similarproblem->problem_similar_id);
                $problemlink = $this->lipsoutput->action_link(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'problem', 'problemId' => $similarproblem->problem_similar_id)), $problemdetails[$similarproblem->problem_similar_id]->problem_label);
                $creatorlink = $this->lipsoutput->action_link(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'profile', 'id_user' => $problemdetails[$similarproblem->problem_similar_id]->user_id)), ucfirst($problemdetails[$similarproblem->problem_similar_id]->firstname) . ' ' . strtoupper($problemdetails[$similarproblem->problem_similar_id]->lastname));
                echo $this->lipsoutput->display_p($problemlink . ' ' . get_string('from', 'lips') . ' ' . $creatorlink);
            }
        }

        // Create ace
        $this->lipsoutput->display_ace_form('answerEditor', 'id_problem_answer', $lips->coloration_language, 'resolution');

        // Challenge dialog
        $userid = get_user_details(array("id_user_moodle" => $USER->id))->id;
        echo $this->lipsoutput->display_challenge_dialog($categorydetails->category_name, $details[$this->id]->problem_label, fetch_challenged_users($userid, $this->id));
        echo '<input type="hidden" id="hiddenLIPSid" value="' . $lips->id . '"/>';
        echo '<input type="hidden" id="hiddenProblemid" value="' . $this->id . '"/>';
    }
}

/**
 * Display a message of confirmation for the deletion of a problem.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_delete_problem extends page_view {
    private $id;
    private $originv;
    private $originaction;
    private $categoryid;

    function  __construct($cm, $id, $originv, $originaction, $categoryid = null) {
        parent::__construct($cm, "deleteProblem");
        $this->id = $id;
        $this->originv = $originv;
        $this->originaction = $originaction;
        $this->categoryid = $categoryid;
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        $details = get_problem_details($this->id);
        $message = $this->lipsoutput->display_h2(get_string('administration_delete_problem_confirmation', 'lips') . " " . $details[$this->id]->problem_label . " ?");

        $continueurl = new moodle_url('action.php', array('id' => $this->cm->id, 'action' => $this->view, 'originV' => $this->originv, 'originAction' => $this->originaction, 'problemId' => $this->id, 'categoryId' => $this->categoryid));
        if ($this->originaction != null) {
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv, 'action' => $this->originaction, 'categoryId' => $this->categoryid));
        } else {
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv, 'categoryId' => $this->categoryid));
        }
        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}

/**
 * Display a message of confirmation for the deletion of problems.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_delete_problems extends page_view {

    function  __construct($cm) {
        parent::__construct($cm, "deleteProblems");
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/../form/mod_lips_problem_form.php');
        $message = "";
        $serializedidproblems = optional_param("idproblems", null, PARAM_TEXT);
        $count = 0;
        foreach (unserialize($serializedidproblems) as $idproblem) {
            $count++;
            $problemdetailsarray = get_problem_details($idproblem);
            $problemdetails = $problemdetailsarray[$idproblem];
            $message .= $this->lipsoutput->display_p($problemdetails->problem_label);
        }
        if ($count > 1) {
            $title = $this->lipsoutput->display_h2(get_string('administration_delete_problems_confirmation', 'lips'));
        } else {
            $title = $this->lipsoutput->display_h2(get_string('administration_delete_problem_confirmation_msg', 'lips'));
        }
        $continueurl = new moodle_url('action.php', array('id' => $this->cm->id, 'idproblems' => $serializedidproblems, 'action' => 'deleteProblems'));
        $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'problem_category_select_delete'));
        echo $this->lipsoutput->confirm($title . $message, $continueurl, $cancelurl);
    }
}

/**
 * Display solutions of a problem.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_solutions extends page_view {
    private $id;

    function  __construct($cm, $id) {
        parent::__construct($cm, "solutions");
        $this->id = $id;
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        require_once(dirname(__FILE__) . '/../form/mod_lips_search_form.php');

        $lips = get_current_instance();

        $details = get_problem_details($this->id);
        echo $this->lipsoutput->display_h2($details[$this->id]->problem_label);
        $author = $this->lipsoutput->display_span(get_string("problem_author", "lips"), array("class" => "label_field_page_problem")) . " " . $this->lipsoutput->display_user_link($details[$this->id]->user_id, $details[$this->id]->firstname, $details[$this->id]->lastname);
        echo $this->lipsoutput->display_p($author, array("class" => "field_page_problem"));
        $datecreation = $this->lipsoutput->display_span(get_string("problem_date_creation", "lips"), array("class" => "label_field_page_problem")) . " " . date("d/m/y", $details[$this->id]->problem_date);
        echo $this->lipsoutput->display_p($datecreation, array("class" => "field_page_problem"));
        $nbresolutions = $this->lipsoutput->display_span(get_string("problem_nb_resolutions", "lips"), array("class" => "label_field_page_problem")) . " " . $details[$this->id]->problem_resolutions . " / " . $details[$this->id]->problem_attempts . " " . get_string("attempts", "lips");
        echo $this->lipsoutput->display_p($nbresolutions, array("class" => "field_page_problem"));
        $difficulty = $this->lipsoutput->display_span(get_string("difficulty", "lips"), array("class" => "label_field_page_problem")) . " " . get_string($details[$this->id]->difficulty_label, "lips");
        echo $this->lipsoutput->display_p($difficulty, array("class" => "field_page_problem"));
        $prerequisite = $details[$this->id]->problem_preconditions;
        if (empty($prerequisite)) {
            $prerequisite = get_string("none", "lips");
        }
        $prerequisite = $this->lipsoutput->display_span(get_string("prerequisite", "lips"), array("class" => "label_field_page_problem")) . " " . $prerequisite;
        echo $this->lipsoutput->display_p($prerequisite, array("class" => "field_page_problem"));

        $searchform = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'problemId' => $this->id)), null, 'post', '', array('class' => 'search-form', 'style' => 'width: 60%'));
        $searchform->display();

        $search = null;
        if ($searchform->is_submitted()) {
            $data = $searchform->get_submitted_data();
            if (!empty($data->inputSearch)) {
                $search = $data->inputSearch;
            }
        }
        $solutions = get_solutions($this->id, $search);
        foreach ($solutions as $solution) {
            $this->lipsoutput->display_solution($solution, $lips);
        }
    }
}