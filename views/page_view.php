<?php

/**
 * Page view
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT & Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
        $this->add_script_tag('./js/jquery.js');
        $this->add_script_tag('./scripts.js');
        $this->add_script_tag('./ace/ace-builds/src-noconflict/ace.js');
        $this->add_script_tag("//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js");
        $this->add_script_tag("./ace/ace-builds/src-noconflict/ext-language_tools.js");
        $this->add_css_tag("//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css");
        $this->add_css_tag('./styles.css');
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