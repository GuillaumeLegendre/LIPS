<?php
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
        $categories = array();
        foreach (fetch_all_categories(get_current_instance()->id) as $category) {
            $categories[$category->id] = $category->category_name;
        }

        $difficulties = array();
        foreach (fetch_all_difficulties() as $difficulty) {
            $difficulties[$difficulty->id] = $difficulty->difficulty_label;
        }
        $output = $PAGE->get_renderer('mod_lips');

        // Preconfig.
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_preconfig_subtitle", "lips")));
        $mform->addElement('html', (get_string("administration_language_code_msg", "lips")));

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
        $mform->addElement('textarea', 'textAreaImports', get_string("administration_problem_create_code_import_label", "lips"), 'rows="15" cols="100"');
        $mform->addElement('textarea', 'problem_code', get_string("administration_problem_create_code_complete_label", "lips"), 'rows="15" cols="100"');
        $mform->addElement('textarea', 'problem_unit_tests', get_string("administration_problem_create_code_unittest_label", "lips"), 'rows = "15" cols = "100"');
        $mform->addRule('problem_unit_tests', get_string('administration_language_form_select_unittests_error', 'lips'), 'required', null, 'client');

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