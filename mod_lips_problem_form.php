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

        // /!\ DO NOT DELETE.
        $mform->addElement('select', 'correction', null);

        // Preconfig
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_preconfig_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_language_code_msg", "lips"));

        // Global Informations
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

        // Subject
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_subject_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_subject_msg", "lips"));

        $mform->addElement('editor', 'problem_statement', get_string("subject", "lips"));
        $mform->addRule('problem_statement', get_string('administration_language_form_select_subject_error', 'lips'), 'required', null, 'client');
        $mform->addElement('editor', 'problem_tips', get_string("tips", "lips"));

        // Code
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_code_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_code_msg", "lips"));

        // Textarea for the imports
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

        // Create button
        $mform->addElement('submit', 'submit', get_string('create', 'lips'));
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
        return $errors;
    }

    /**
     * Handle the form
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     */
    public function handle($instance) {
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
        $data->problem_date = time();
        $data->problem_creator_id = $USER->id;
        $data->problem_statement = $data->problem_statement['text'];
        $data->problem_tips = $data->problem_tips['text'];
        $DB->insert_record('lips_problem', $data);

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
class mod_lips_problem_delete_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE, $USER;
        $mform =& $this->_form;
        $problems="";
        foreach (fetch_problems($USER->id) as $problem) {
            $problems[$problem->id] = $problem->problem_label;
        }
        $mform->addElement('select', 'problemId', get_string('administration_problem_modify_select', 'lips'), $problems);
        // Delete button.
        $mform->addElement('submit', 'submit', get_string('delete', 'lips'));
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

        $mform =& $this->_form;
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

        // /!\ DO NOT DELETE.
        $mform->addElement('select', 'correction', null);

        // Preconfig
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_preconfig_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_language_code_msg", "lips"));

        // Global Informations
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

        // Subject
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_subject_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_subject_msg", "lips"));

        $mform->addElement('editor', 'problem_statement', get_string("subject", "lips"));
        $mform->addRule('problem_statement', get_string('administration_language_form_select_subject_error', 'lips'), 'required', null, 'client');
        $mform->addElement('editor', 'problem_tips', get_string("tips", "lips"));

        // Code
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_code_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_code_msg", "lips"));

        // Textarea for the imports
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

        // Create button
        $mform->addElement('submit', 'submit', get_string('create', 'lips'));
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
        return $errors;
    }

    /**
     * Handle the form
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     */
    public function handle($instance) {
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
        $data->problem_date = time();
        $data->problem_creator_id = $USER->id;
        $data->problem_statement = $data->problem_statement['text'];
        $data->problem_tips = $data->problem_tips['text'];
        $DB->insert_record('lips_problem', $data);

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_problem_create_success', 'lips'), 'SUCCESS');
    }
}