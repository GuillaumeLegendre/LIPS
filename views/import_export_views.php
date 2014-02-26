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

require_once(dirname(__FILE__) . '/page_view.php');

/**
 * Confirmation page to redirect on Moodle restore course in order to import problems.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_import_problems extends page_view {

    /**
     * page_import_problems constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_import_problems content
     */
    function display_content() {
        global $PAGE;

        $context = $PAGE->context;
        $coursecontext = $context->get_course_context();

        // Moodle restore view.
        $continueurl = new moodle_url('../../backup/restorefile.php', array('contextid' => $coursecontext->id));
        $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => "administration"));

        $message = $this->lipsoutput->display_h2(get_string('administration_problems_import_confirmation', 'lips'));

        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}

/**
 * Confirmation page to redirect on Moodle backup course in order to export problems.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_export_problems extends page_view {

    /**
     * page_export_problems constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_export_problems content
     */
    function display_content() {
        global $PAGE;

        $context = $PAGE->context;
        $coursecontext = $context->get_course_context();
        $cm = get_coursemodule_from_id('lips', optional_param('id', 0, PARAM_INT), 0, false, MUST_EXIST);

        // Moodle backup view only for the lips instance.
        $continueurl = new moodle_url('../../backup/backup.php', array('id' => $coursecontext->instanceid, 'cm' => $cm->id));

        $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => "administration"));

        $message = $this->lipsoutput->display_h2(get_string('administration_problems_export_confirmation', 'lips'));

        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}
