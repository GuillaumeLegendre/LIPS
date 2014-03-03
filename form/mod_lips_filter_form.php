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
        
        // Header.
        $mform->addElement('header', 'headerSearch', get_string('filter', 'lips'));
        $mform->addElement('select', 'language_id_js', null, $activelanguages);

        // Input search.
        $mform->addElement('text', 'userSearch', null, array('placeholder' => $placeholder, 'class' => $clazz));
        $mform->setType('userSearch', PARAM_TEXT);

        // Search button.
        $mform->addElement('submit', 'submit', get_string('filter', 'lips'), array('class' => 'lips-button'));
    }
}