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

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once(dirname(__FILE__) . '/../locallib.php');

/**
 * Language configuration form
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_configure_language_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE, $CFG;

        // Get web service class from the file config.
        $config = parse_ini_file($CFG->dirroot . "/mod/lips/config.ini", true);
        require_once(dirname(__FILE__) . '/../webservices/' .
            $config['web_services']['service_languages_class'] . '.php');
        $mform =& $this->_form;

        // Current language informations.
        $lips = get_current_instance();

        // Select the language.
        $languages = $config['web_services']['service_languages_class']::get_list_languages();
        $currentlanguage = get_current_instance()->compile_language;
        if (!$languages) {
            echo $PAGE->get_renderer('mod_lips')->display_notification(
                get_string('web_service_communication_error', 'lips'), 'ERROR');
        } else {
            $languagesused = get_active_languages();
            foreach ($languagesused as $language) {
                if ($language->compile_language != $currentlanguage) {
                    unset($languages[$language->compile_language]);
                }
            }
            $mform->addElement('select', 'selectLanguage',
                get_string('administration_language_form_select', 'lips'), $languages);
            $mform->addRule('selectLanguage',
                get_string('administration_language_form_select_error', 'lips'), 'required', null, 'client');
            if ($lips->compile_language != null) {
                $mform->setDefault('selectLanguage', $lips->compile_language);
            }
        }

        // Select the syntax highlighting.
        $mform->addElement('select',
            'selectSyntaxHighlighting',
            get_string('administration_language_form_highlighting_select', 'lips'),
            ace_available_languages());
        $mform->addRule('selectSyntaxHighlighting',
            get_string('administration_language_form_select_error', 'lips'),
            'required', null, 'client');
        if ($lips->coloration_language != null) {
            $mform->setDefault('selectSyntaxHighlighting', $lips->coloration_language);
        }
        $mform->addElement('text', 'inputComment', get_string('administration_language_form_input', 'lips'));
        $mform->setType('inputComment', PARAM_TEXT);
        $mform->setDefault('inputComment', $lips->comment_format);

        // Modify button.
        $mform->addElement('submit', 'submit', get_string('modify', 'lips'), array('class' => 'lips-button'));
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

        if (isset($data->selectSyntaxHighlighting) && isset($data->inputComment)) {
            if (isset($data->selectLanguage) && empty($data->selectLanguage)) {
                $errors['emptySelectLanguage'] = get_string('administration_language_form_select_error', 'lips');
            }

            if (empty($data->selectSyntaxHighlighting)) {
                $errors['emptySelectLanguage'] = get_string('administration_language_form_highlighting_select_error', 'lips');
            }
        } else {
            $errors['impossibleError'] = get_string('error_impossible', 'lips');
        }

        return $errors;
    }

    /**
     * Handle the form
     *
     */
    public function handle() {
        global $PAGE;

        // Do nothing if not submitted or cancelled.
        if (!$this->is_submitted() || $this->is_cancelled()) {
            return;
        }

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

        // Update the data.
        $lips = get_current_instance();

        if (isset($data->selectLanguage)) {
            update_language($lips->id, array(
                'compile_language' => $data->selectLanguage,
                'coloration_language' => $data->selectSyntaxHighlighting,
                'comment_format' => $data->inputComment
            ));
        } else {
            update_language($lips->id, array(
                'coloration_language' => $data->selectSyntaxHighlighting,
                'comment_format' => $data->inputComment
            ));
        }

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_language_configure_success', 'lips'),
            'SUCCESS');
    }

}

/**
 * Language picture form
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_configure_picture_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $CFG;
        $mform =& $this->_form;

        // Select the image.
        $mform->addElement('text', 'filePicture', get_string('administration_language_form_file', 'lips'), array("size" => 40));
        $mform->setType('filePicture', PARAM_TEXT);

        // Modify button.
        $mform->addElement('submit', 'submit', get_string('modify', 'lips'), array('class' => 'lips-button'));
    }

    /**
     * Handle the form
     *
     */
    public function handle() {
        global $PAGE;

        // Do nothing if not submitted or cancelled.
        if (!$this->is_submitted() || $this->is_cancelled()) {
            return;
        }

        // Form data.
        $data = $this->get_submitted_data();

        if (empty($data->filePicture)) {
            echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_language_image_empty',
                    'lips'),
                'ERROR');

            return;
        }

        if (!is_writable('images/')) {
            echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_language_image_rights',
                    'lips'),
                'ERROR');

            return;
        }

        // Picture.
        $url = $data->filePicture;
        $explodepicture = explode("/", $url);
        $picture = $explodepicture[count($explodepicture) - 1];

        if (!is_a_picture($picture)) {
            echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_language_image_type_error',
                    'lips'),
                'ERROR');
            return;
        }

        // Save the picture.
        $picture = download_image($url);

        if ($picture !== false) {
            // Current instance.
            $lips = get_current_instance();

            // Delete the old image.
            if ($lips->language_picture != $picture && $lips->language_picture != 'default-language.png') {
                delete_picture_file($lips->language_picture);
            }

            // Update the picture on the database.
            update_language_picture($picture);

            // Success message.
            echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_language_image_success', 'lips'),
                'SUCCESS');
        } else {
            echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_language_image_url_error',
                    'lips'),
                'ERROR');
        }
    }
}

/**
 * Language base code form
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_configure_code_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE;

        $mform =& $this->_form;
        $mcustomdata = $this->_customdata;

        // Textarea for base code.
        $mform->addElement('html', '<div id="configEditor" class="ace">' . (($mcustomdata['base_code'] == null ||
                $mcustomdata['base_code'] == '') ? '' : htmlspecialchars($mcustomdata['base_code'])) . '</div>');
        $mform->addElement('textarea', 'areaBaseCode', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));
        $mform->setDefault('areaBaseCode', $mcustomdata['base_code']);

        // Modify button.
        $mform->addElement('submit', 'submit', get_string('modify', 'lips'), array('class' => 'lips-button'));
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

        if (isset($data->areaBaseCode)) {
            preg_match_all('/<lips-preconfig-import\/>/', $data->areaBaseCode, $import);
            preg_match_all('/<lips-preconfig-code\/>/', $data->areaBaseCode, $code);
            preg_match_all('/<lips-preconfig-tests\/>/', $data->areaBaseCode, $tests);

            if (count($import[0]) == 0) {
                $errors['noImports'] = get_string('administration_language_code_imports_error_no', 'lips');
            }
            if (count($import[0]) > 1) {
                $errors['tooManyImports'] = get_string('administration_language_code_imports_error', 'lips');
            }
            if (count($code[0]) == 0) {
                $errors['noCode'] = get_string('administration_language_code_code_error_no', 'lips');
            }
            if (count($code[0]) > 1) {
                $errors['tooManyCode'] = get_string('administration_language_code_code_error', 'lips');
            }
            if (count($tests[0]) == 0) {
                $errors['noTests'] = get_string('administration_language_code_tests_error_no', 'lips');
            }
            if (count($tests[0]) > 1) {
                $errors['tooManyTests'] = get_string('administration_language_code_tests_error', 'lips');
            }
        } else {
            $errors['impossibleError'] = get_string('error_impossible', 'lips');
        }

        return $errors;
    }

    /**
     * Handle the form
     *
     */
    public function handle() {
        global $PAGE;

        // Do nothing if not submitted or cancelled.
        if (!$this->is_submitted() || $this->is_cancelled()) {
            return;
        }

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

        // Update the data.
        $lips = get_current_instance();
        update_language($lips->id, array('base_code' => $data->areaBaseCode));

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_language_code_success', 'lips'),
            'SUCCESS');
    }
}