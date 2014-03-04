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
 * Achievement selection form
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_achievement_select_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $CFG, $PAGE;
        $mform =& $this->_form;

        // Select the category.
        $lips = get_current_instance();

        $categoriesarray = array();
        $achievementsarray = array();

        $achievements = fetch_categories_and_achievements($lips->id);
        foreach ($achievements as $achievement) {
            $categoriesarray[$achievement->categoryid] = $achievement->category_name;
            $achievementsarray[$achievement->categoryid][$achievement->achievementid] = $achievement->achievement_label;
        }

        if (count($categoriesarray) != 0) {
            $categoriescount = (count($categoriesarray) < 10) ? count($categoriesarray) : 10;

            // Select achievement.
            $hier =& $mform->addElement('hierselect',
                'selectAchievement',
                get_string('achievements', 'lips'),
                array('size' => $categoriescount));
            $hier->setOptions(array($categoriesarray, $achievementsarray));
            $mform->addRule('selectAchievement',
                get_string('administration_category_modify_select_error', 'lips'),
                'required', null, 'client');

            // Modify button.
            $mform->addElement('submit', 'submit', get_string('modify', 'lips'), array('class' => 'lips-button'));
        } else {
            $html = $PAGE->get_renderer('mod_lips')->display_notification(get_string("administration_empty_achievements", "lips"),
                'WARNING');
            $mform->addElement('html', $html);
            $mform->addElement('html', '<br/><br/><br/><br/><br/><br/>');
        }
    }
}

/**
 * Achievement form
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_achievement_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $CFG;
        $mform =& $this->_form;
        $mcustomdata = $this->_customdata;

        // Category ID.
        $mform->addElement('hidden', 'inputAchievementID', null, null);
        $mform->setType('inputAchievementID', PARAM_INT);
        $mform->setDefault('inputAchievementID', $mcustomdata['id']);

        // Label.
        $mform->addElement('text', 'inputLabel', get_string('label', 'lips'), array('size' => 48));
        $mform->addRule('inputLabel', get_string('administration_achievement_label_error', 'lips'), 'required', null, 'client');
        $mform->setType('inputLabel', PARAM_TEXT);
        $mform->setDefault('inputLabel', $mcustomdata['achievement_label']);

        // Description.
        $mform->addElement('textarea', 'areaDescription', get_string('description', 'lips'), array('cols' => 50, 'rows' => 10));
        $mform->addRule('areaDescription', get_string('administration_achievement_area_error', 'lips'), 'required', null, 'client');
        $mform->setDefault('areaDescription', $mcustomdata['achievement_desc']);

        // Picture.
        $mform->addElement('text', 'inputPicture', get_string('picture', 'lips'), array('size' => 48));
        $mform->setType('inputPicture', PARAM_TEXT);
        $mform->setDefault('inputPicture', $mcustomdata['achievement_picture']);

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

        // The validation failed.
        $errors = $this->validation($data, null);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo $PAGE->get_renderer('mod_lips')->display_notification($error, 'ERROR');
            }

            return;
        }

        // Update date.
        $achievementdetails = get_achievement_details(array('id' => $data->inputAchievementID));
        if (empty($data->inputPicture)) {
            switch ($achievementdetails->achievement_problems) {
                case 10:
                    $data->inputPicture = 'bronze.png';
                    break;
                case 25:
                    $data->inputPicture = 'silver.png';
                    break;
                case 50:
                    $data->inputPicture = 'gold.png';
                    break;
                case 100:
                    $data->inputPicture = 'platinum.png';
                    break;
            }

            echo $PAGE->get_renderer('mod_lips')->display_notification(
                get_string('administration_achievement_default_picture', 'lips'),
                'INFO');
        } else {
            if (!is_a_picture($data->inputPicture)) {
                echo $PAGE->get_renderer('mod_lips')->display_notification(
                    get_string('administration_language_image_type_error', 'lips'),
                    'ERROR');

                return;
            }
        }

        update_achievement(array(
            'id' => $achievementdetails->id,
            'achievement_label' => $data->inputLabel,
            'achievement_desc' => $data->areaDescription,
            'achievement_picture' => $data->inputPicture
        ));

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(
            get_string('administration_achievement_success', 'lips'),
            'SUCCESS');
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

        if (isset($data->inputLabel) && isset($data->areaDescription) && isset($data->inputPicture)) {
            if (empty($data->inputLabel)) {
                $errors['emptyAchievementLabel'] = get_string('administration_achievement_label_error', 'lips');
            }

            if (empty($data->areaDescription)) {
                $errors['emptyAchievementDesc'] = get_string('administration_achievement_area_error', 'lips');
            }
        } else {
            $errors['impossibleError'] = get_string('error_impossible', 'lips');
        }

        return $errors;
    }
}