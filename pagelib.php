<?php
require_once($CFG->dirroot . '/tag/lib.php');

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

        // Add the custom stylesheet
        echo '<script type="text/javascript" src="js/jquery.js"></script><script type="text/javascript">$(document).ready(function () {$(\'head\').append(\'<link rel="stylesheet" type="text/css" href="styles.css">\');});</script>';
    }
}

/**
 * Page to list the categories
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_list_categories extends page_view {

    /**
     * page_list_categories constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "problems");
    }

    /**
     * Display the page_list_categories content
     */
    function display_content() {
        global $CFG;
        require_once("$CFG->libdir/tablelib.php");
        require_once(dirname(__FILE__) . '/categories_table.php');
        $table = new categories_table($this->cm);
        $table->out(10, true);
    }
}

/**
 * Index page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_index extends page_view {

    /**
     * page_index constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "index");
    }

    /**
     * Display the page_index content
     */
    function display_content() {
        global $USER;

        echo $this->lipsoutput->display_h1('Bienvenue ' . $USER->username);
    }
}

/**
 * Administration page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got & Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin extends page_view {

    /**
     * page_admin constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin content
     */
    function display_content() {
        global $CFG;

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();
        echo '<br/><br/><br/><br/>';
    }
}

/**
 * Language configuration page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_langage extends page_view {

    /**
     * page_admin constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_configure_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Configure language
        echo $this->lipsoutput->display_h2(get_string('administration_language_configure_title', 'lips'));
        echo $this->lipsoutput->display_p(get_string('administration_language_configure_msg', 'lips'));

        $configureLanguageForm = new mod_lips_configure_language_form("test.html", null, 'post');
        $configureLanguageForm->display();

        // Modify language picture
        $this->lipsoutput->display_h2(get_string('administration_language_image_title', 'lips'));
        $this->lipsoutput->display_p(get_string('administration_language_image_msg', 'lips'));
        echo '<center>' . $this->lipsoutput->display_img(get_language_picture(), array('id' => 'testimg', 'width' => '64px', 'height' => '64px')) . '</center>';

        $configurePictureForm = new mod_lips_configure_picture_form("test.html", null, 'post');
        $configurePictureForm->display();

        // Language base code
        $this->lipsoutput->display_h2(get_string('administration_language_code_title', 'lips'));
        $this->lipsoutput->display_p(get_string('administration_language_code_msg', 'lips'));

        $configureCodeForm = new mod_lips_configure_code_form("test.html", null, 'post');
        $configureCodeForm->display();
    }
}

/**
 * Category creation page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_category_create extends page_view {

    /**
     * page_admin constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_category_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Create a category
        echo $this->lipsoutput->display_h2(get_string('administration_category_create_title', 'lips'));
        echo $this->lipsoutput->display_p(get_string('administration_category_msg', 'lips'));

        $createCategoryForm = new mod_lips_category_create_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_create')), null, 'post');

        if($createCategoryForm->is_submitted()) {
            $createCategoryForm->handle($this->cm->instance);
            $createCategoryForm->display();
        } else {
            $createCategoryForm->display();
        }
        
        /*//Form processing and displaying is done here
        if ($mform->is_cancelled()) {
            //Handle form cancel operation, if cancel button is present on form
        } else if ($fromform = $mform->get_data()) {
            global $DB;
            unset($fromform->submitbutton);
            $DB->insert_record("lips_category", $fromform);
            echo "Category created";
        } else {
            // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
            // or on the first display of the form.

            //Set default data (if any)
            //displays the form
            $mform->display();
        }*/
    }
}

/**
 * Page to list the users
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_users extends page_view {

    /**
     * page_users constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "users");
    }

    /**
     * Display the page_users content
     */
    function display_content() {
        global $CFG, $OUTPUT;
        require "$CFG->libdir/tablelib.php";

        $table = new table_sql("mdl_lips_user");
        $table->set_sql("*", "mdl_lips_user", "1");
        $table->define_baseurl(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view)));
        $table->define_headers(array("Nom", "Prenom"));
        $table->define_columns(array("user_name", "user_first_name"));
        $table->sortable(true);
        $table->out(10, true);
    }
}

/**
 * Profil page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profil extends page_view {
    private $cmid;

    /**
     * page_profil constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm, $cmid) {
        parent::__construct($cm, "profil");
        $this->cmid = $cmid;
    }

    /**
     * Display the page_profil content
     */
    function display_content() {
        global $OUTPUT, $USER, $DB;

        $id = $DB->get_record('course', array('id' => $this->cm->course), '*', MUST_EXIST);
        $avatar = new user_picture($USER);
        $avatar->courseid = $this->cmid;
        $avatar->link = true;
        echo $OUTPUT->render($avatar) . " ";
        echo $USER->firstname . " " . $USER->lastname;
    }

}

/**
 * Category content
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_category extends page_view {
    private $id;

    /**
     * page_profil constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm, $id) {
        parent::__construct($cm, "category");
        $this->id = $id;
    }

    /**
     * Display the page_category content
     */
    function display_content() {
        echo "<h1>" . get_category_details($this->id)->category_name . "</h1>";
        require_once(dirname(__FILE__) . '/problems_table.php');
        $table = new problems_table("mdl_lips_problem");
        $table->out(10, true);
    }
}