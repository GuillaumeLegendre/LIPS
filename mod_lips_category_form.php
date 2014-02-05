<?php
require_once($CFG->dirroot . '/course/moodleform_mod.php');

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
    function definition() {
        $mform =& $this->_form;

        // Category name
        $mform->addElement('text', 'inputCategoryName', get_string('category', 'lips'), array('size' => '64', 'maxlength' => '255', 'placeholder' => get_string('name', 'lips')));
        $mform->setType('inputCategoryName', PARAM_TEXT);
        $mform->addRule('inputCategoryName', get_string('administration_category_name_error', 'lips'), 'required');

        // Category documentation (LINK)
        $mform->addElement('text', 'inputCategoryDocumentation', get_string('documentation', 'lips'), array('size' => '64', 'placeholder' => get_string('administration_category_documentation_placeholder', 'lips')));
        $mform->setType('inputCategoryDocumentation', PARAM_TEXT);

        // Category documentation (TEXT)
        $mform->addElement('textarea', 'areaCategoryDocumentation', null, 'rows="15" cols="100"');

        // Create button
        $mform->addElement('submit', 'submit', get_string('create', 'lips'));
    }
}