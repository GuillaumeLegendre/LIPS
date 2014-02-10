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

        // Add scripts
        $this->add_script_tag('./js/jquery.js');
        $this->add_script_tag('./scripts.js');
        $this->add_script_tag('./ace/ace-builds/src-noconflict/ace.js');
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
class page_admin_langage_configure extends page_view {

    /**
     * page_admin_langage_configure constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_langage_configure content
     */
    function display_content() {
        global $CFG, $PAGE;
        require_once(dirname(__FILE__) . '/mod_lips_configure_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Configure language
        echo $this->lipsoutput->display_h2(get_string('administration_language_configure_title', 'lips'));
        echo $this->lipsoutput->display_p(get_string('administration_language_configure_msg', 'lips'));

        $lips = get_current_instance();
        if(count_languages_number($lips->id) > 0)
            echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_existing_problems', 'lips'), 'ERROR');

        $configureLanguageForm = new mod_lips_configure_language_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_configure')), null, 'post');

        if($configureLanguageForm->is_submitted()) {
            $configureLanguageForm->handle();
            $configureLanguageForm->display();
        } else {
            $configureLanguageForm->display();
        }
    }
}

/**
 * Page to modify the language picture
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_langage_picture extends page_view {

    /**
     * page_admin_langage_picture constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_langage_picture content
     */
    function display_content() {
        global $CFG, $PAGE;
        require_once(dirname(__FILE__) . '/mod_lips_configure_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Modify language picture
        echo $this->lipsoutput->display_h2(get_string('administration_language_image_title', 'lips'), array('id' => 'picture'));
        echo $this->lipsoutput->display_p(get_string('administration_language_image_msg', 'lips'));
        echo '<center>' . $this->lipsoutput->display_img(get_language_picture(), array('width' => '64px', 'height' => '64px')) . '</center>';

        $configurePictureForm = new mod_lips_configure_picture_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_picture')), null, 'post');

        if($configurePictureForm->is_submitted()) {        
            $configurePictureForm->handle();
            $configurePictureForm->display();
        } else {
            $configurePictureForm->display();
        }
    }
}

/**
 * Page to modify the language base code
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_langage_base extends page_view {

    /**
     * page_admin_langage_base constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_langage_base content
     */
    function display_content() {
        global $CFG, $PAGE;
        require_once(dirname(__FILE__) . '/mod_lips_configure_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Language base code
        echo $this->lipsoutput->display_h2(get_string('administration_language_code_title', 'lips'), array('id' => 'code'));
        echo $this->lipsoutput->display_p(get_string('administration_language_code_msg', 'lips'));

        $lips = get_current_instance();
        if($lips->compile_language == null && has_role('adminplugin'))
            echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_no_compile_language', 'lips'), 'ERROR');
        if($lips->coloration_language == null && has_role('adminplugin'))
            echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_no_syntax_highlighting', 'lips'), 'WARNING');

        $lips = get_current_instance();
        $configureCodeForm = new mod_lips_configure_code_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_base')), (array) $lips, 'post');

        if($configureCodeForm->is_submitted()) {
            $configureCodeForm->handle();

            // /!\ Do no remove. Used to refresh the Ace code with the updated data
            $lips = get_current_instance();
            $configureCodeForm = new mod_lips_configure_code_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_base')), (array) $lips, 'post');
            $configureCodeForm->display();
        } else {
            $configureCodeForm->display();
        }

        $this->lipsoutput->display_ace_form('configEditor', 'id_areaBaseCode', $lips->coloration_language);
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
            $createCategoryForm->handle();
            $createCategoryForm->display();
        } else {
            $createCategoryForm->display();
        }
    }
}

/**
 * Page to select the category to modify
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_category_select_modify extends page_view {

    /**
     * page_admin_category_select_modify constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_category_select_modify content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_category_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Modify a category
        echo $this->lipsoutput->display_h2(get_string('administration_category_modify_title', 'lips'));

        $modifySelectCategoryForm = new mod_lips_category_modify_select_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')), null, 'post');

        if($modifySelectCategoryForm->is_submitted()) {
            $modifySelectCategoryForm->handle();
            $modifySelectCategoryForm->display();
        } else {
            $modifySelectCategoryForm->display();
        }
    }
}

/**
 * Category modification page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_category_modify extends page_view {

    /**
     * page_admin_category_modify constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_category_modify content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_category_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Modify a category
        echo $this->lipsoutput->display_h2(get_string('administration_category_modify_title', 'lips'));
        echo $this->lipsoutput->display_p(get_string('administration_category_msg', 'lips'));

        $modifyCategoryForm = new mod_lips_category_modify_form();

        if($modifyCategoryForm->is_submitted()) {
            $modifyCategoryForm = new mod_lips_category_modify_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')), null, 'post');

            $modifyCategoryForm->handle();
            $modifyCategoryForm->display();
        } else {
            $categoryid = optional_param('category_id', null, PARAM_INT);

            if($categoryid == null) {
                $modifySelectCategoryForm = new mod_lips_category_modify_select_form();
                $data = $modifySelectCategoryForm->get_submitted_data();
                $categorydetails = get_category_details($data->selectCategory);

                $modifyCategoryForm = new mod_lips_category_modify_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')), (array) $categorydetails, 'post');

                $modifyCategoryForm->display();
            } else {
                $categorydetails = get_category_details($categoryid);

                $modifyCategoryForm = new mod_lips_category_modify_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')), (array) $categorydetails, 'post');

                $modifyCategoryForm->display();
            }

        }
    }
}

/**
 * Category delete page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_category_delete extends page_view {

    /**
     * page_admin_category_delete constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_category_delete content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_category_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Delete a category
        echo $this->lipsoutput->display_h2(get_string('administration_category_delete_title', 'lips'));

        $deleteCategoryForm = new mod_lips_category_delete_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => 'deleteCategory', 'originV' => $this->view, 'originAction' => 'category_delete')), null, 'post');
        $deleteCategoryForm->display();
    }
}

/**
 * Problem creation page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_problem_create extends page_view {

    /**
     * page_admin_problem_create constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_problem_create content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_problem_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Create a category
        echo $this->lipsoutput->display_h2(get_string('administration_problem_create_title', 'lips'));

        $createProblemForm = new mod_lips_problem_create_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problem_create')), null, 'post');

        if($createProblemForm->is_submitted()) {
            $createProblemForm->handle($this->cm->instance);
            $createProblemForm->display();
        } else {
            $createProblemForm->display();
        }
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
        $details=get_category_details($this->id);
        echo $this->lipsoutput->display_top_page_category($details->category_name, $this->id);
        require_once(dirname(__FILE__) . '/problems_table.php');
        $table = new problems_table($this->cm, $this->id);
        $table->out(10, true);
    }
}

/**
 * Display the documentation of a category
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_category_documentation extends page_view {
    private $id;

    /**
     * page_documentation constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm, $id) {
        parent::__construct($cm, "categoryDocumentation");
        $this->id = $id;
    }

    /**
     * Display the category_documentation content
     */
    function display_content() {
        $details = get_category_details($this->id);
        echo $this->lipsoutput->display_h2($details->category_name . " - " . get_string('documentation', 'lips'));
        echo $details->category_documentation;
    }
}

/**
 * Display a message of confirmation for the deletion of a category.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_delete_category extends page_view {
    private $id;
    private $originv;
    private $originaction;

    function  __construct($cm, $id, $originv, $originaction) {
        parent::__construct($cm, "deleteCategory");

        $this->id = $id;
        $this->originv = $originv;
        $this->originaction = $originaction;
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        $details = get_category_details($this->id);
        $message = $this->lipsoutput->display_h2(get_string('administration_delete_category_confirmation', 'lips') . " " . $details->category_name . " ?");

        $continueurl = new moodle_url('action.php', array('id' => $this->cm->id, 'action' => $this->view, 'originV' => $this->originv, 'originAction' => $this->originaction, 'categoryId' => $this->id));
        if($this->originaction != null)
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv, 'action' => $this->originaction));
        else
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv));

        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}