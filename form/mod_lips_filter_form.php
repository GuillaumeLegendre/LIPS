<?php
global $CFG;
require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once(dirname(__FILE__) . '/../locallib.php');

/**
 * Filter form
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_filter_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE;
        $mform =& $this->_form;
        $mcustomdata = $this->_customdata;

        $placeholder = (isset($mcustomdata['placeholder'])) ? $mcustomdata['placeholder'] : '';
        $clazz = (isset($mcustomdata['class'])) ? $mcustomdata['class'] : '';

        $activelanguages = array();
        $activelanguages["all"] = "Tout";
        $categorieslanguage["all"] = "Tout";
        foreach (get_active_languages() as $language) {
            $activelanguages[$language->id] = $language->compile_language;
        }
        // Header
        $mform->addElement('header', 'headerSearch', get_string('filter', 'lips'));
        $mform->addElement('select', 'language_id_js', null, $activelanguages);
        $mform->addElement('select', 'category_id_js', null, $categorieslanguage);

        // Input search
        $mform->addElement('text', 'userSearch', null, array('placeholder' => $placeholder, 'class' => $clazz));
        $mform->setType('userSearch', PARAM_TEXT);

        // Search button.
        $mform->addElement('submit', 'submit', get_string('filter', 'lips'));
    }
}