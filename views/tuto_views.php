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
 * Language tutorial
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_tuto_language extends page_view {

    /**
     * page_tuto_language constructor
     *
     * @param object $cm Moodle context
     */
    public function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_tuto_language content
     */
    protected function display_content() {
        global $CFG;

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Tutorial.
        echo file_get_contents('tutorials/language.html');
    }
}

/**
 * Problem tutorial
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_tuto_problem extends page_view {

    /**
     * page_tuto_problem constructor
     *
     * @param object $cm Moodle context
     */
    public function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_tuto_problem content
     */
    protected function display_content() {
        global $CFG;

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Tutorial.
        echo file_get_contents('tutorials/problem.html');
    }
}

/**
 * Resolution tutorial
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_tuto_resolution extends page_view {

    /**
     * page_tuto_resolution constructor
     *
     * @param object $cm Moodle context
     */
    public function  __construct($cm) {
        parent::__construct($cm, "problem");
    }

    /**
     * Display the page_tuto_resolution content
     */
    protected function display_content() {
        global $CFG;

        // Tutorial.
        echo file_get_contents('tutorials/resolution.html');
    }
}