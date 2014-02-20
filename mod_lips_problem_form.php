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

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once(dirname(__FILE__) . '/locallib.php');

/**
 * Form to create a problem
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problem_create_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE;

        $mform =& $this->_form;
        $output = $PAGE->get_renderer('mod_lips');
        // Fetch all categories.
        $categories = array();
        foreach (fetch_all_categories(get_current_instance()->id) as $category) {
            $categories[$category->id] = $category->category_name;
        }

        // Fetch all difficulties.
        $difficulties = array();
        foreach (fetch_all_difficulties() as $difficulty) {
            $difficulties[$difficulty->id] = get_string($difficulty->difficulty_label, "lips");
        }

        // /!\ DO NOT DELETE.
        $mform->addElement('select', 'correction', null);

        // Preconfig.
        $lips = get_current_instance();
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_preconfig_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_language_code_msg", "lips"));

        if ($lips->base_code != null) {
            $mform->addElement('html', '<div id="preconfigEditor" class="ace" style="margin: auto;">' . htmlspecialchars($lips->base_code) . '</div>');
        }

        // Global Informations.
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_informations_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_informations_msg", "lips"));

        $mform->addElement('select', 'problem_category_id', get_string('category', 'lips'), $categories);
        $mform->addRule('problem_category_id', get_string('administration_language_form_select_category_error', 'lips'), 'required', null, 'client');

        $mform->addElement('select', 'problem_difficulty_id', get_string('difficulty', 'lips'), $difficulties);
        $mform->addRule('problem_difficulty_id', get_string('administration_language_form_select_difficulty_error', 'lips'), 'required');

        $mform->addElement('text', 'problem_label', get_string('problem', 'lips'), array('size' => '64', 'maxlength' => '255', 'placeholder' => get_string('name', 'lips')));
        $mform->addRule('problem_label', get_string('administration_language_form_select_name_error', 'lips'), 'required', null, 'client');
        $mform->setType('problem_label', PARAM_TEXT);

        $mform->addElement('text', 'problem_preconditions', get_string('prerequisite', 'lips'), array('size' => '64', 'maxlength' => '255'));
        $mform->setType('problem_preconditions', PARAM_TEXT);

        // Subject.
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_subject_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_subject_msg", "lips"));

        $mform->addElement('editor', 'problem_statement', get_string("subject", "lips"));
        $mform->addRule('problem_statement', get_string('administration_language_form_select_subject_error', 'lips'), 'required', null, 'client');
        $mform->addElement('editor', 'problem_tips', get_string("tips", "lips"));

        // Code.
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_code_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_code_msg", "lips"));

        // Textarea for the imports.
        $mform->addElement('html', '<p class="acetitle">' . get_string("administration_problem_create_code_import_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="importsEditor" class="ace"></div>');
        $mform->addElement('textarea', 'problem_imports', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));

        // Textarea for the code to complete
        $mform->addElement('html', '<p class="acetitle required">' . get_string("administration_problem_create_code_complete_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="problemCodeEditor" class="ace"></div>');
        $mform->addElement('textarea', 'problem_code', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));

        // Textarea for the unit tests
        $mform->addElement('html', '<p class="acetitle required">' . get_string("administration_problem_create_code_unittest_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="unitTestsEditor" class="ace"></div>');
        $mform->addElement('textarea', 'problem_unit_tests', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));

        /*--------------------------------------------------
        * Similar problems
        *------------------------------------------------*/
        $categorieswithproblems = array();
        foreach (fetch_all_categories_with_problems() as $category) {
            $categorieswithproblems[$category->problem_category_id] = $category->category_name;
        }

        $problems = array();
        foreach (fetch_problems_by_category(key($categorieswithproblems)) as $problem) {
            $problems[$problem->id] = $problem->problem_label;
        }

        // Hidden field to store id of similar problems.
        $mform->addElement('hidden', 'problems_similar', null, array("id" => "id_problem_similar"));
        $mform->setType('problems_similar', PARAM_TEXT);
        $mform->addElement('html', '<div id="dialog" title="Conseil de problèmes similaires"><h2>Conseil - ' . $lips->compile_language . '</h2>');
        $mform->addElement('select', 'problem_category_id_js', get_string('category', 'lips'),
            $categorieswithproblems, array('class' => 'text ui-widget-content ui-corner-all', 'style' => 'width:95%'));
        $mform->addElement('select', 'problem_id_js', get_string('problem', 'lips'),
            $problems, array('class' => 'text ui-widget-content ui-corner-all', 'style' => 'width:95%'));
        $mform->addElement('html', '</div>');

        $mform->addElement('html', $output->display_h3(get_string("administration_problem_similar_subtitle", "lips")));
        $mform->addElement('html', $output->display_p(get_string("administration_problem_similar_subtitle_msg", "lips")));
        $mform->addElement('html', '<div id="problem_similar_content">');
        $mform->addElement('html', '</div>');
        $mform->addElement('button', 'intro',
            get_string("administration_problem_modify_select", "lips"), array('class' => 'problem_similar', 'id' => 'problem_similar_button'));

        /*--------------------------------------------------
        * Submit
        *------------------------------------------------*/

        // Create & Test button
        $mform->addElement('submit', 'submit', get_string('create', 'lips'));
        $mform->addElement('submit', 'submit', get_string('test_problem', 'lips'));
    }


    /**
     * Form custom validation
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     * @return array Errors array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $errors = array();
        if (isset($data->problem_label) && isset($data->problem_category_id) && isset($data->problem_statement) && isset($data->problem_difficulty_id) && isset($data->problem_unit_tests)) {
            if (empty($data->problem_label)) {
                $errors['emptyProblemLabel'] = get_string('administration_language_form_select_name_error', 'lips');
            } else if (problem_exists($data->problem_label, $data->problem_category_id)) {
                $errors['alreadyExists'] = get_string('administration_problem_already_exists', 'lips');
            }
            if (empty($data->problem_category_id)) {
                $errors['emptyCategoryId'] = get_string('administration_language_form_select_category_error', 'lips');
            }
            if (empty($data->problem_statement)) {
                $errors['emptyProblemStatement'] = get_string('administration_language_form_select_subject_error', 'lips');
            }
            if (empty($data->problem_difficulty_id)) {
                $errors['emptyProblemDifficulty'] = get_string('administration_language_form_select_difficulty_error', 'lips');
            }
            if (empty($data->problem_code)) {
                $errors['emptyProblemCode'] = get_string('administration_language_form_code_error', 'lips');
            }
            if (empty($data->problem_unit_tests)) {
                $errors['emptyProblemUnittests'] = get_string('administration_unittests_form_code_error', 'lips');
            }
        } else {
            $errors['impossibleError'] = get_string('error_impossible', 'lips');
        }
        return $errors;
    }

    /**
     * Handle the form
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     */
    public function handle() {
        global $DB, $USER, $PAGE;

        // Do nothing if not submitted or cancelled.
        if (!$this->is_submitted() || $this->is_cancelled())
            return;

        // Form data.
        $data = $this->get_submitted_data();

        // The validation failed.
        $errors = $this->validation($data, null);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo $PAGE->get_renderer('mod_lips')->display_notification($error, 'ERROR');
            }
            return;
        }

        $problemssimilarid = array_unique(explode(" ", $data->problems_similar));

        // Insert the problem
        $data->problem_date = time();
        $data->problem_creator_id = $USER->id;
        $data->problem_statement = $data->problem_statement['text'];
        $data->problem_tips = $data->problem_tips['text'];
        if ($data->submit == get_string('test_problem', 'lips')) {
            $data->problem_testing = 1;
        }
        $problemid = $DB->insert_record('lips_problem', $data);
        foreach ($problemssimilarid as $problemsimilar) {
            if (!empty($problemsimilar)) {
                insert_problem_similar($problemid, $problemsimilar);
            }
        }

        // Insert notifications
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
        $lips = get_current_instance();
        insert_notification($lips->id, $userdetails->id, 'notification_problem_created', time(), $userdetails->id, null, $problemid);
        $followers = fetch_followers($userdetails->id);
        foreach ($followers as $follower) {
            insert_notification($lips->id, $follower->follower, 'notification_problem_created', time(), $userdetails->id, null, $problemid);
        }

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_problem_create_success', 'lips'), 'SUCCESS');
    }
}

/**
 * Form to delete a problem
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problems_delete_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE, $USER;
        $mcustomdata = $this->_customdata;
        $mform =& $this->_form;
        $hasproblems = false;
        foreach (fetch_problems_user_by_category($USER->id, $mcustomdata['idcategory']) as $problem) {
            $hasproblems = true;
            $mform->addElement('advcheckbox', $problem->id, null, $problem->problem_label, array('group' => 1), array(0, 1));
        }
        if ($hasproblems) {
            $mform->addElement('submit', 'submit', get_string('delete', 'lips'));
        } else {
            $msg = get_string("administration_empty_problems", "lips");
            $html = $PAGE->get_renderer('mod_lips')->display_notification($msg, 'WARNING');
            $mform->addElement('html', $html);
            $mform->addElement('html', '<br/><br/><br/><br/><br/><br/>');
        }
    }
}

/**
 * Form to modify a problem
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problem_modify_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE;

        $mcustomdata = $this->_customdata;
        $mform =& $this->_form;
        $lips = get_current_instance();
        $output = $PAGE->get_renderer('mod_lips');

        // Fetch all categories
        $categories = array();
        foreach (fetch_all_categories(get_current_instance()->id) as $category) {
            $categories[$category->id] = $category->category_name;
        }

        // Fetch all difficulties
        $difficulties = array();
        foreach (fetch_all_difficulties() as $difficulty) {
            $difficulties[$difficulty->id] = get_string($difficulty->difficulty_label, "lips");
        }
        
        // ID.
        $mform->addElement('hidden', 'id_problem', null, null);
        $mform->setType('id_problem', PARAM_INT);
        $mform->setDefault('id_problem', $mcustomdata['id']);

        // Preconfig
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_preconfig_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_language_code_msg", "lips"));
        if ($lips->base_code != null) {
            $mform->addElement('html', '<div id="preconfigEditor" class="ace" style="margin: auto;">' . htmlspecialchars($lips->base_code) . '</div>');
        }
        // Global Informations
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_informations_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_informations_msg", "lips"));

        $mform->addElement('select', 'problem_category_id', get_string('category', 'lips'), $categories)->setSelected($mcustomdata['problem_category_id']);
        $mform->addRule('problem_category_id', get_string('administration_language_form_select_category_error', 'lips'), 'required', null, 'client');

        $mform->addElement('select', 'problem_difficulty_id', get_string('difficulty', 'lips'), $difficulties)->setSelected($mcustomdata['difficulty_id']);
        $mform->addRule('problem_difficulty_id', get_string('administration_language_form_select_difficulty_error', 'lips'), 'required');

        $mform->addElement('text', 'problem_label', get_string('problem', 'lips'), array('size' => '64', 'maxlength' => '255', 'placeholder' => get_string('name', 'lips')));
        $mform->addRule('problem_label', get_string('administration_language_form_select_name_error', 'lips'), 'required', null, 'client');
        $mform->setType('problem_label', PARAM_TEXT);
        $mform->setDefault('problem_label', $mcustomdata['problem_label']);

        $mform->addElement('text', 'problem_preconditions', get_string('prerequisite', 'lips'), array('size' => '64', 'maxlength' => '255'));
        $mform->setType('problem_preconditions', PARAM_TEXT);
        $mform->setDefault('problem_preconditions', $mcustomdata['problem_preconditions']);

        // Subject
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_subject_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_subject_msg", "lips"));

        $mform->addElement('editor', 'problem_statement', get_string("subject", "lips"))->setValue(array('text' => $mcustomdata['problem_statement']));
        $mform->addRule('problem_statement', get_string('administration_language_form_select_subject_error', 'lips'), 'required', null, 'client');
        $mform->addElement('editor', 'problem_tips', get_string("tips", "lips"))->setValue(array('text' => $mcustomdata['problem_tips']));

        // Code
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_code_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_code_msg", "lips"));

        // Textarea for the imports
        $mform->addElement('html', '<p class="acetitle">' . get_string("administration_problem_create_code_import_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="importsEditor" class="ace">' . htmlspecialchars($mcustomdata['problem_imports']) . '</div>');
        $mform->addElement('textarea', 'problem_imports', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));
        $mform->setDefault('problem_imports', $mcustomdata['problem_imports']);

        // Textarea for the code to complete
        $mform->addElement('html', '<p class="acetitle required">' . get_string("administration_problem_create_code_complete_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="problemCodeEditor" class="ace">' . htmlspecialchars($mcustomdata['problem_code']) . '</div>');
        $mform->addElement('textarea', 'problem_code', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));
        $mform->setDefault('problem_code', $mcustomdata['problem_code']);

        // Textarea for the unit tests
        $mform->addElement('html', '<p class="acetitle required">' . get_string("administration_problem_create_code_unittest_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="unitTestsEditor" class="ace">' . htmlspecialchars($mcustomdata['problem_unit_tests']) . '</div>');
        $mform->addElement('textarea', 'problem_unit_tests', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));
        $mform->setDefault('problem_unit_tests', $mcustomdata['problem_unit_tests']);

        /*--------------------------------------------------
       * Similar problems
       *------------------------------------------------*/
        $categorieswithproblems = array();
        foreach (fetch_all_categories_with_problems() as $category) {
            $categorieswithproblems[$category->problem_category_id] = $category->category_name;
        }
        $problems = array();
        foreach (fetch_problems_by_category(key($categorieswithproblems)) as $problem) {
            $problems[$problem->id] = $problem->problem_label;
        }
        // Hidden field to store id of similar problems.
        $mform->setType('problems_similar', PARAM_TEXT);
        $mform->addElement('html', '<div id="dialog" title="Conseil de problèmes similaires"><h2>Conseil - ' . $lips->compile_language . '</h2>');
        $mform->addElement('select', 'problem_category_id_js', get_string('category', 'lips'),
            $categorieswithproblems, array('class' => 'text ui-widget-content ui-corner-all', 'style' => 'width:95%'));
        $mform->addElement('select', 'problem_id_js', get_string('problem', 'lips'),
            $problems, array('class' => 'text ui-widget-content ui-corner-all', 'style' => 'width:95%'));
        $mform->addElement('html', '</div>');
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_similar_subtitle", "lips")));
        $mform->addElement('html', $output->display_p(get_string("administration_problem_similar_subtitle_msg", "lips")));
        $mform->addElement('html', '<div id="problem_similar_content">');
        $mform->addElement('html', '</div>');

        // Display stored relation of similar problems
        $idproblem = $mcustomdata['id'];
        $valuehiddenfield = "";
        if (isset($idproblem)) {
            $similarproblems = get_similar_problems($mcustomdata['id']);
            foreach ($similarproblems as $similarproblem) {
                $mform->addElement('html', "<div class='fitem fitem_ftext similar_problem'>
                <div class='felement ftext'><input readonly type='text' name='select_problem_similar_$similarproblem->problem_similar_id' value='$similarproblem->problem_label'></div>
                 <input class='delete_problem_similar_button' id='$similarproblem->problem_similar_id' src='./images/delete_similar.png' type='image'></div>");

                $valuehiddenfield .= " " . $similarproblem->problem_similar_id;
            }
        }
        $mform->addElement('hidden', 'problems_similar', $valuehiddenfield, array("id" => "id_problem_similar"));
        $mform->addElement('button', 'intro',
            get_string("administration_problem_modify_select", "lips"), array('class' => 'problem_similar', 'id' => 'problem_similar_button'));
        // Create button
        $mform->addElement('submit', 'submit', get_string('edit', 'lips'));
    }

    /**
     * Form custom validation
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     * @return array Errors array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $errors = array();
        if (isset($data->problem_label) && isset($data->problem_category_id) && isset($data->problem_statement) && isset($data->problem_difficulty_id) && isset($data->problem_unit_tests)) {
            if (empty($data->problem_label)) {
                $errors['emptyProblemLabel'] = get_string('administration_language_form_select_name_error', 'lips');
            } else if (problem_exists($data->problem_label, $data->problem_category_id)) {
                $errors['alreadyExists'] = get_string('administration_problem_already_exists', 'lips');
            }
            if (empty($data->problem_category_id)) {
                $errors['emptyCategoryId'] = get_string('administration_language_form_select_category_error', 'lips');
            }
            if (empty($data->problem_statement)) {
                $errors['emptyProblemStatement'] = get_string('administration_language_form_select_subject_error', 'lips');
            }
            if (empty($data->problem_difficulty_id)) {
                $errors['emptyProblemDifficulty'] = get_string('administration_language_form_select_difficulty_error', 'lips');
            }
            if (empty($data->problem_code)) {
                $errors['emptyProblemCode'] = get_string('administration_language_form_code_error', 'lips');
            }
            if (empty($data->problem_unit_tests)) {
                $errors['emptyProblemUnittests'] = get_string('administration_unittests_form_code_error', 'lips');
            }
        } else {
            $errors['impossibleError'] = get_string('error_impossible', 'lips');
        }
        return $errors;
    }

    /**
     * Handle the form
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     */
    public function handle($instance) {
        global $USER, $PAGE;

        // Do nothing if not submitted or cancelled.
        if (!$this->is_submitted() || $this->is_cancelled()) {
            return;
        }

        // Form data.
        $data = $this->get_submitted_data();

        // The validation failed.
        $data->id = $data->id_problem;
        $errors = $this->validation($data, null);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo $PAGE->get_renderer('mod_lips')->display_notification($error, 'ERROR');
            }
            return;
        }

        $data->problem_date = time();
        $data->problem_creator_id = $USER->id;
        $data->problem_statement = $data->problem_statement['text'];
        $data->problem_tips = $data->problem_tips['text'];

        // Update
        update_problem($data);
        delete_problems_similar($data->id);
        $problemssimilarid = array_unique(explode(" ", $data->problems_similar));
        foreach ($problemssimilarid as $problemsimilar) {
            if (!empty($problemsimilar)) {
                if (!problem_similar_exist($data->id, $problemsimilar)) {
                    insert_problem_similar($data->id, $problemsimilar);
                }
            }
        }

        // Insert notifications
        $lips = get_current_instance();
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
        insert_notification($lips->id, $userdetails->id, 'notification_problem_modified', time(), $userdetails->id, null, $data->id);
        $followers = fetch_followers($userdetails->id);
        foreach ($followers as $follower) {
            insert_notification($lips->id, $follower->follower, 'notification_problem_modified', time(), $userdetails->id, null, $data->id);
        }

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_problem_modify_success', 'lips'), 'SUCCESS');
    }
}

/**
 * Form to select the category to modify
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problem_modify_select_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $USER;
        $mform =& $this->_form;

        // Select the category.
        $lips = get_current_instance();
        $problems = array();
        foreach (fetch_problems($USER->id) as $problem) {
            $problems[$problem->id] = $problem->problem_label;
        }

        if (count($problems) != 0) {
            $mform->addElement('select', 'problemId', get_string('administration_problem_modify_select', 'lips'), $problems);
            $mform->addRule('problemId', get_string('administration_category_modify_select_error', 'lips'), 'required', null, 'client');

            // Modify button.
            $mform->addElement('submit', 'submit', get_string('modify', 'lips'));
        } else {
            $msg = get_string("administration_empty_problems", "lips");
            $html = $PAGE->get_renderer('mod_lips')->display_notification($msg, 'WARNING');
            $mform->addElement('html', $html);
            $mform->addElement('html', '<br/><br/><br/><br/><br/><br/>');
        }

    }
}

/**
 * Form to answer to a problem.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problems_resolve_form extends moodleform {
    /**
     * Form definition
     */
    public function definition() {
        global $PAGE, $CFG;
        $mform =& $this->_form;
        $mcustomdata = $this->_customdata;

        if($mcustomdata['idproblem'] != null) {
            $code = htmlspecialchars(get_code_to_resolve($mcustomdata['idproblem']));
            $mform->addElement('html', '<div id="answerEditor"" class="ace">' . htmlspecialchars(get_code_to_resolve($mcustomdata['idproblem'])) . '</div>');
            $mform->addElement('textarea', 'problem_answer', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));
            $mform->setDefault('problem_answer', $code);
        } else {
            $mform->addElement('html', '<div id="answerEditor"" class="ace"></div>');
            $mform->addElement('textarea', 'problem_answer', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));
        }
        
        // Export button.
        $mform->addElement('submit', 'submit', get_string('send_response', 'lips'));
    }
}