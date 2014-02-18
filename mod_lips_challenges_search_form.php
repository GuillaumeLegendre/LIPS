<?php
global $CFG;
require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once(dirname(__FILE__) . '/locallib.php');

/**
 * Search form for challenges
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_challenges_search_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE;
        $mform =& $this->_form;

        // Header
        $mform->addElement('header', 'headerSearch', get_string('search', 'lips'));

        // Input search for problem
        $mform->addElement('text', 'problemInputSearch', null,
            array(
                'size' => '64',
                'maxlength' => '255',
                'placeholder' => get_string('problem', 'lips')
            ));
        $mform->setType('problemInputSearch', PARAM_TEXT);

        // Input search for author
        $mform->addElement('text', 'authorInputSearch', null,
            array(
                'size' => '64',
                'maxlength' => '255',
                'placeholder' => get_string('author', 'lips')
            ));
        $mform->setType('authorInputSearch', PARAM_TEXT);

        // Search button.
        $mform->addElement('submit', 'submit', get_string('search', 'lips'));
    }
}