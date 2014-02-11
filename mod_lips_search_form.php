<?php
global $CFG;
require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once(dirname(__FILE__) . '/locallib.php');

/**
 * Search form 
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_search_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE;
        $mform =& $this->_form;

        // Header
        $mform->addElement('header', 'headerSearch', get_string('search', 'lips'));

        // Input search
        $mform->addElement('text', 'inputSearch');
        $mform->setType('inputSearch', PARAM_TEXT);

        // Search button.
        $mform->addElement('submit', 'submit', get_string('search', 'lips'));
    }
}