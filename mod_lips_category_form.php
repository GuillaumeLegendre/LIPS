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
 * Form to create a category
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_category_create_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        $mform =& $this->_form;

        // Category name.
        $mform->addElement('text', 'inputCategoryName', get_string('category', 'lips'),
            array(
                'size' => '64',
                'maxlength' => '255',
                'placeholder' => get_string('name', 'lips')
            )
        );
        $mform->setType('inputCategoryName', PARAM_TEXT);
        $mform->addRule('inputCategoryName', get_string('administration_category_name_error', 'lips'), 'required', null, 'client');

        // Category documentation (LINK).
        $mform->addElement('text', 'inputCategoryDocumentation', get_string('administration_category_documentation_link', 'lips'),
            array(
                'size' => '64',
                'placeholder' => get_string('administration_category_documentation_link_placeholder', 'lips')));
        $mform->setType('inputCategoryDocumentation', PARAM_TEXT);

        // Category documentation (TEXT)
        $mform->addElement('editor', 'areaCategoryDocumentation', get_string('administration_category_documentation_text', 'lips'), 'rows="15" cols="100" placeholder="' . get_string('administration_category_documentation_text_placeholder', 'lips') . '"');

        // Create button.
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
        $errors = array();

        if (isset($data->inputCategoryName) && isset($data->inputCategoryDocumentation) && isset($data->areaCategoryDocumentation)) {
            if (!empty($data->inputCategoryName)) {
                if (category_exists(array('category_name' => $data->inputCategoryName))) {
                    $errors['alreadyExists'] = get_string('administration_category_already_exists', 'lips');
                }

                if (!empty($data->inputCategoryDocumentation) && $data->areaCategoryDocumentation['text'] != "") {
                    $errors['bothLinkAndText'] = get_string('administration_category_documentation_error', 'lips');
                }
            } else {
                $errors['emptyCategoryName'] = get_string('administration_category_name_error', 'lips');
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

        // Params
        $categoryname = $data->inputCategoryName;
        $categorydocumentation = (empty($data->inputCategoryDocumentation)) ? $data->areaCategoryDocumentation['text'] : $data->inputCategoryDocumentation;
        $categorydocumentationtype = (empty($data->inputCategoryDocumentation)) ? ((!empty($data->areaCategoryDocumentation['text'])) ? 'TEXT' : null) : 'LINK';

        // Insert the data.
        $lips = get_current_instance();
        insert_category($lips->id, $categoryname, $categorydocumentation, $categorydocumentationtype);

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_category_create_success', 'lips'), 'SUCCESS');
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
class mod_lips_category_modify_select_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        $mform =& $this->_form;

        // Select the category.
        $lips = get_current_instance();
        $categories = array();
        foreach (fetch_all_categories($lips->id) as $category) {
            $categories[$category->id] = $category->category_name;
        }

        $mform->addElement('select', 'selectCategory',
            get_string('administration_category_modify_select', 'lips'), $categories);
        $mform->addRule('selectCategory',
            get_string('administration_category_modify_select_error', 'lips'), 'required', null, 'client');

        // Modify button.
        $mform->addElement('submit', 'submit', get_string('modify', 'lips'));
    }
}

/**
 * Form to modify a category
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_category_modify_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        $mform =& $this->_form;
        $mcustomdata = $this->_customdata;

        // Category ID.
        $mform->addElement('hidden', 'inputCategoryID', null, null);
        $mform->setType('inputCategoryID', PARAM_INT);
        $mform->setDefault('inputCategoryID', $mcustomdata['id']);

        // Category current name.
        $mform->addElement('hidden', 'inputCategoryCurrentName', null, null);
        $mform->setType('inputCategoryCurrentName', PARAM_TEXT);
        $mform->setDefault('inputCategoryCurrentName', $mcustomdata['category_name']);

        // Category name.
        $mform->addElement('text', 'inputCategoryName',
            get_string('category', 'lips'), array(
                'size' => '64',
                'maxlength' => '255',
                'placeholder' => get_string('name', 'lips')));
        $mform->setType('inputCategoryName', PARAM_TEXT);
        $mform->setDefault('inputCategoryName', $mcustomdata['category_name']);
        $mform->addRule('inputCategoryName',
            get_string('administration_category_name_error', 'lips'), 'required', null, 'client');

        // Category documentation (LINK).
        $mform->addElement('text', 'inputCategoryDocumentation',
            get_string('administration_category_documentation_link', 'lips'), array(
                'size' => '64',
                'placeholder' => get_string('administration_category_documentation_link_placeholder', 'lips')));
        if ($mcustomdata['category_documentation_type'] == 'LINK') {
            $mform->setDefault('inputCategoryDocumentation', $mcustomdata['category_documentation']);
        }
        $mform->setType('inputCategoryDocumentation', PARAM_TEXT);

        // Category documentation (TEXT)
        $mform->addElement('editor', 'areaCategoryDocumentation', get_string('administration_category_documentation_text', 'lips'), 'rows="15" cols="100" placeholder="' . get_string('administration_category_documentation_text_placeholder', 'lips') . '"');
        if($mcustomdata['category_documentation_type'] == 'TEXT') {
            $mform->setDefault('areaCategoryDocumentation', $mcustomdata['category_documentation']);
        }

        // Modify button.
        $mform->addElement('submit', 'submit', get_string('modify', 'lips'));
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

        if(isset($data->inputCategoryName) &&
            isset($data->inputCategoryDocumentation) &&
            isset($data->areaCategoryDocumentation)) {
            if(!empty($data->inputCategoryName)) {
                if($data->inputCategoryCurrentName != $data->inputCategoryName &&
                    category_exists(array('category_name' => $data->inputCategoryName))) {
                    $errors['alreadyExists'] = get_string('administration_category_already_exists', 'lips');
                }

                if(!empty($data->inputCategoryDocumentation) && $data->areaCategoryDocumentation['text'] != "") {
                    $errors['bothLinkAndText'] = get_string('administration_category_documentation_error', 'lips');
                }
            } else {
                $errors['emptyCategoryName'] = get_string('administration_category_name_error', 'lips');
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

        // Params
        $categoryid = $data->inputCategoryID;
        $categoryname = $data->inputCategoryName;
        $categorydocumentation = (empty($data->inputCategoryDocumentation)) ? $data->areaCategoryDocumentation['text'] : $data->inputCategoryDocumentation;
        $categorydocumentationtype = (empty($data->inputCategoryDocumentation)) ? ((!empty($data->areaCategoryDocumentation['text'])) ? 'TEXT' : null) : 'LINK';

        // Update the data.
        update_category($categoryid, $categoryname, $categorydocumentation, $categorydocumentationtype);

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_category_modify_success', 'lips'), 'SUCCESS');
    }
}

/**
 * Form to delete a category
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_category_delete_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        $mform =& $this->_form;

        // Select the category.
        $lips = get_current_instance();
        $categories = array();

        foreach (fetch_removable_categories($lips->id) as $category) {
            $categories[$category->id] = $category->category_name;
        }

        $mform->addElement('select', 'categoryId', get_string('administration_category_modify_select', 'lips'), $categories);
        $mform->addRule('categoryId',
            get_string('administration_category_modify_select_error', 'lips'), 'required', null, 'client');

        // Delete button.
        $mform->addElement('submit', 'submit', get_string('delete', 'lips'));
    }
}