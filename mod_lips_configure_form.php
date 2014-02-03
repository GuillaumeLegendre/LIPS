<?php
require_once ($CFG->dirroot.'/course/moodleform_mod.php');

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
    function definition() {
        $mform =& $this->_form;

        // /!\ Temporary ==> Switch to use the web service
        $languages['java'] = 'Java';
        $languages['c'] = 'C';
        $languages['perl'] = 'Perl';
        $languages['python'] = 'Python';
        $languages['php'] = 'PHP';
        $languages['c#'] = 'C#';

        // Select the language
        $mform->addElement('select', 'selectLanguages', get_string('administration_language_form_select', 'lips'), $languages);
        $mform->addRule('selectLanguages', get_string('administration_language_form_select_error', 'lips'), 'required');

        // Modify button
        $mform->addElement('submit', 'submit', get_string('modify', 'lips'));
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
    function definition() {
        $mform =& $this->_form;

        // Select the image
        $mform->addElement('file', 'filePicture', get_string('administration_language_form_file', 'lips'));
        $mform->addRule('filePicture', get_string('administration_language_form_file_error', 'lips'), 'required');

        // Modify button
        $mform->addElement('submit', 'submit', get_string('modify', 'lips'));
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
    function definition() {
        $mform =& $this->_form;

        // Textarea for base code
        $mform->addElement('textarea', 'areaBaseCode', null, 'rows="15" cols="100"');
        $mform->addRule('areaBaseCode', get_string('administration_language_form_area_error', 'lips'), 'required');

        // Modify button
        $mform->addElement('submit', 'submit', get_string('modify', 'lips'));
    }
}