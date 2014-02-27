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
abstract class page_view {
    protected $cm;
    protected $view;
    protected $lipsoutput;

    /**
     * page_view constructor
     *
     * @param object $cm Moodle context
     * @param object $view Moodle view
     */
    function __construct($cm, $view) {
        global $PAGE;

        $this->cm = $cm;
        $this->view = $view;
        $this->lipsoutput = $PAGE->get_renderer('mod_lips');
    }

    /**
     * Display the view
     */
    function display() {
        $this->display_header();
        $this->display_content();
        $this->display_footer();
    }

    /**
     * Display the header
     */
    function display_header() {
        global $OUTPUT;

        echo $OUTPUT->header();
        echo $this->lipsoutput->tabs($this->view);

        // Add scripts
        $this->add_css_tag("//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css");
        $this->add_css_tag('./styles/styles.css');
        $this->add_script_tag('./js/jquery.js');
        $this->add_script_tag('./scripts.js');
        $this->add_script_tag('./ace/ace-builds/src-noconflict/ace.js');
        $this->add_script_tag("//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js");
        $this->add_script_tag("./ace/ace-builds/src-noconflict/ext-language_tools.js");
    }

    /**
     * Display the denied header
     */
    function display_denied_header() {
        global $OUTPUT;

        echo $OUTPUT->header();
    }

    /**
     * Display the content
     */
    abstract protected function display_content();

    /**
     * Display the footer
     */
    function display_footer() {
        global $OUTPUT;

        echo $OUTPUT->footer();
    }

    /**
     * Add a script tag to the header
     *
     * @param string $script Script to add
     */
    function add_script_tag($script) {
        echo '<script src="' . $script . '" type="text/javascript" charset="utf-8"></script>';
    }

    /**
     * Add a css tag to the header
     *
     * @param string $css Scss to add
     */
    function add_css_tag($css) {
        echo "<link rel='stylesheet' href='" . $css . "'>";
    }
}