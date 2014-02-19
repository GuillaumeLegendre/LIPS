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
        $this->add_script_tag("//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js");
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

        // User details
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));

        // Current challenges
        $currentchallengedetails = fetch_challenges(array('challenge_to' => $userdetails->id, 'challenge_state' => 'ACCEPTED'));
        echo $this->lipsoutput->display_h1(get_string('current_challenges', 'lips'));
        if (count($currentchallengedetails) > 0) {
            echo $this->lipsoutput->display_current_challenges($currentchallengedetails);
        } else {
            echo $this->lipsoutput->display_p(get_string('no_challenges', 'lips'));
        }

        // Received challenges
        $receivedchallengedetails = fetch_challenges(array('challenge_to' => $userdetails->id, 'challenge_state' => 'WAITING'));
        echo $this->lipsoutput->display_h1(get_string('received_challenges', 'lips'), array("style" => "margin-top: 15px"));
        if (count($receivedchallengedetails) > 0) {
            echo $this->lipsoutput->display_challenges($receivedchallengedetails);
        } else {
            echo $this->lipsoutput->display_p(get_string('no_challenges', 'lips'));
        }

        // Notifications
        $notificationsdetails = fetch_notifications_details('notification_user_id = ' . $userdetails->id . ' AND notification_from <> ' . $userdetails->id . ' AND notification_to <> ' . $userdetails->id);
        echo $this->lipsoutput->display_h1(get_string('notifications', 'lips'));
        if (count($notificationsdetails) > 0) {
            echo $this->lipsoutput->display_notifications($notificationsdetails);
        } else {
            echo $this->lipsoutput->display_p(get_string('no_notifications', 'lips'));
        }
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
        echo '<br/><br/><br/><br/><br/><br/><br/><br/><br/>';
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
        if (count_languages_number($lips->id) > 0) {
            echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_existing_problems', 'lips'), 'ERROR');
        }

        $configurelanguageform = new mod_lips_configure_language_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_configure')), null, 'post');

        if ($configurelanguageform->is_submitted()) {
            $configurelanguageform->handle();
            $configurelanguageform->display();
        } else {
            $configurelanguageform->display();
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

        $configurepictureform = new mod_lips_configure_picture_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_picture')), null, 'post');
        if ($configurepictureform->is_submitted()) {
            $configurepictureform->handle();
        }

        // Modify language picture
        echo $this->lipsoutput->display_h2(get_string('administration_language_image_title', 'lips'), array('id' => 'picture'));
        echo $this->lipsoutput->display_p(get_string('administration_language_image_msg', 'lips') . formatBytes($CFG->portfolio_moderate_filesize_threshold) . '.');
        echo '<center>' . $this->lipsoutput->display_img(get_language_picture(), array('width' => '64px', 'height' => '64px')) . '</center>';

        $configurepictureform->display();
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
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_configure_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Language base code
        echo $this->lipsoutput->display_h2(get_string('administration_language_code_title', 'lips'), array('id' => 'code'));
        echo $this->lipsoutput->display_p(get_string('administration_language_code_msg', 'lips'));

        $lips = get_current_instance();
        if ($lips->compile_language == null && has_role('administration'))
            echo $this->lipsoutput->display_notification(get_string('administration_no_compile_language', 'lips'), 'ERROR');
        if ($lips->coloration_language == null && has_role('administration'))
            echo $this->lipsoutput->display_notification(get_string('administration_no_syntax_highlighting', 'lips'), 'WARNING');

        $lips = get_current_instance();
        $configurecodeform = new mod_lips_configure_code_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_base')), (array)$lips, 'post');

        if ($configurecodeform->is_submitted()) {
            $configurecodeform->handle();

            // /!\ Do no remove. Used to refresh the Ace code with the updated data
            $lips = get_current_instance();
            $configurecodeform = new mod_lips_configure_code_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_base')), (array)$lips, 'post');
            $configurecodeform->display();
        } else {
            $configurecodeform->display();
        }

        // Create ace
        $this->lipsoutput->display_ace_form('configEditor', 'id_areaBaseCode', $lips->coloration_language, 'configure');
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

        if ($createCategoryForm->is_submitted()) {
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

        if ($modifySelectCategoryForm->is_submitted()) {
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

        if ($modifyCategoryForm->is_submitted()) {
            $modifyCategoryForm = new mod_lips_category_modify_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')), null, 'post');

            $modifyCategoryForm->handle();
            $modifyCategoryForm->display();
        } else {
            $categoryid = optional_param('category_id', null, PARAM_INT);

            if ($categoryid == null) {
                $modifySelectCategoryForm = new mod_lips_category_modify_select_form();
                $data = $modifySelectCategoryForm->get_submitted_data();
                $categorydetails = get_category_details($data->selectCategory);

                $modifyCategoryForm = new mod_lips_category_modify_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')), (array)$categorydetails, 'post');

                $modifyCategoryForm->display();
            } else {
                $categorydetails = get_category_details($categoryid);

                $modifyCategoryForm = new mod_lips_category_modify_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')), (array)$categorydetails, 'post');

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
 * Problem modification page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_problem_modify extends page_view {

    private $id;

    /**
     * page_admin_problem_modify constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm, $id) {
        parent::__construct($cm, "administration");
        $this->id = $id;
    }

    /**
     * Display the page_admin_problem
     * _modify content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_problem_form.php');

        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        echo $this->lipsoutput->display_administration_menu();
        echo $this->lipsoutput->display_h2(get_string('administration_problem_modify_title', 'lips'));

        $modifyproblemform = new mod_lips_problem_modify_form();
        if ($modifyproblemform->is_submitted()) {
            $modifyproblemform = new mod_lips_problem_modify_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problem_modify', 'problemId' => $this->id)), null, "post");
            $modifyproblemform->handle($this->cm->instance);
        }
        $problem_details = get_problem_details($this->id);
        $modifyproblemform = new mod_lips_problem_modify_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problem_modify', 'problemId' => $this->id)), (array)$problem_details[$this->id], 'post', '', array('class' => 'problem-form'));

        $modifyproblemform->display();

        $lips = get_current_instance();

        // Create ace
        $this->lipsoutput->display_ace_form('preconfigEditor', '', $lips->coloration_language, 'readonly');
        $this->lipsoutput->display_ace_form('importsEditor', 'id_problem_imports', $lips->coloration_language, '');
        $this->lipsoutput->display_ace_form('problemCodeEditor', 'id_problem_code', $lips->coloration_language, 'code');
        $this->lipsoutput->display_ace_form('unitTestsEditor', 'id_problem_unit_tests', $lips->coloration_language, 'unit-test');
    }
}

/**
 * Page to select the problem to modify
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_problem_select_modify extends page_view {

    /**
     * page_admin_problem_select_modify constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_problem_select_modify content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_problem_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Modify a problem
        echo $this->lipsoutput->display_h2(get_string('administration_problem_modify_title', 'lips'));

        $modifySelectProblemForm = new mod_lips_problem_modify_select_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problem_modify')), null, '');
        $modifySelectProblemForm->display();

    }
}

/**
 * Page to select the category of the problems to delete
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_problem_category_select_delete extends page_view {

    /**
     * page_admin_problem_select_modify constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_problem_select_modify content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_category_form.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Modify a problem
        echo $this->lipsoutput->display_h2(get_string('administration_problem_delete_title', 'lips'));

        $modifySelectCategoryForm = new mod_lips_category_select_problems_delete_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problems_delete')), null, 'get');

        if ($modifySelectCategoryForm->is_submitted()) {
            $modifySelectCategoryForm->handle();
            $modifySelectCategoryForm->display();
        } else {
            $modifySelectCategoryForm->display();
        }
    }
}

/**
 * Problem delete page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_problem_delete extends page_view {

    private $idcategory;

    /**
     * page_admin_problem_delete constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm, $idcategory) {
        parent::__construct($cm, "administration");
        $this->idcategory = $idcategory;
    }

    /**
     * Display the view
     */
    function display() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_problem_form.php');
        $deleteProblemForm = new mod_lips_problems_delete_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'problems_delete', 'idcategory' => $this->idcategory)), array('idcategory' => $this->idcategory), 'post');
        if ($deleteProblemForm->is_submitted()) {
            $categories = array();
            foreach ($deleteProblemForm->get_data() as $problem => $state) {
                if ($state == 1) {
                    $categories[] = $problem;
                }
            }
            redirect(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => 'deleteProblems', 'categories' => serialize($categories))));
            return;
        }
        parent::display_header();
        $this->display_content();
        $deleteProblemForm->display();
        parent::display_footer();
    }

    /**
     * Display the page_admin_problem_delete content
     */
    function display_content() {


        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // Delete a problem
        echo $this->lipsoutput->display_h2(get_string('administration_problem_delete_title', 'lips'));
    }
}

/**
 * Problem creation page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
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

        // Create a problem
        echo $this->lipsoutput->display_h2(get_string('administration_problem_create_title', 'lips'));

        $lips = get_current_instance();
        if ($lips->compile_language == null && has_role('administration'))
            echo $this->lipsoutput->display_notification(get_string('administration_no_compile_language', 'lips'), 'ERROR');
        if ($lips->coloration_language == null && has_role('administration'))
            echo $this->lipsoutput->display_notification(get_string('administration_no_syntax_highlighting', 'lips'), 'WARNING');

        $createProblemForm = new mod_lips_problem_create_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problem_create')), null, 'post', '', array('class' => 'problem-form'));

        if ($createProblemForm->is_submitted()) {
            $createProblemForm->handle();
            $createProblemForm->display();
        } else {
            $createProblemForm->display();
        }

        // Create ace
        $this->lipsoutput->display_ace_form('preconfigEditor', '', $lips->coloration_language, 'readonly');
        $this->lipsoutput->display_ace_form('importsEditor', 'id_problem_imports', $lips->coloration_language, '');
        $this->lipsoutput->display_ace_form('problemCodeEditor', 'id_problem_code', $lips->coloration_language, 'code');
        $this->lipsoutput->display_ace_form('unitTestsEditor', 'id_problem_unit_tests', $lips->coloration_language, 'unit-test');
    }
}

/**
 * Teacher problems
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_my_problems extends page_view {

    /**
     * page_admin_my_problems constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_my_problems content
     */
    function display_content() {
        global $CFG, $USER;

        require_once(dirname(__FILE__) . '/my_problems_table.php');

        // Administration title
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu
        echo $this->lipsoutput->display_administration_menu();

        // My problems
        echo $this->lipsoutput->display_h2(get_string('administration_my_problems_title', 'lips'));

        $lips = get_current_instance();
        $table = new my_problems_table($this->cm, $lips->id, $USER->id);
        $table->out(get_string('my_problems_table', 'lips'), true);
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
        global $CFG, $PAGE;

        require_once(dirname(__FILE__) . '/users_table.php');
        require_once(dirname(__FILE__) . '/mod_lips_search_form.php');

        // Users title
        echo $this->lipsoutput->display_h1(get_string('users', 'lips'));

        $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view)), null, 'post', '', array('class' => 'search-form'));
        $searchForm->display();

        $search = null;
        if ($searchForm->is_submitted()) {
            $data = $searchForm->get_submitted_data();
            if (!empty($data->inputSearch)) {
                $search = $data->inputSearch;
            }
        }

        $table = new users_table($this->cm, $search);
        $table->out(get_string('users_table', 'lips'), true);
    }
}

/**
 * Profile page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile extends page_view {

    /**
     * page_profile constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile content
     */
    function display_content() {
        global $USER;

        $iduser = optional_param('id_user', null, PARAM_TEXT);
        if ($iduser == null) {
            $iduser = get_user_details(array('id_user_moodle' => $USER->id))->id;
        }

        // Profile menu
        echo $this->lipsoutput->display_profile_menu('profile') . '<br/>';

        // Recent activity
        echo $this->lipsoutput->display_h1(get_string('recent_activity', 'lips'));
        echo $this->lipsoutput->display_notifications(fetch_notifications_details('notification_user_id = ' . $iduser . ' AND (notification_from = ' . $iduser . ' OR notification_to = ' . $iduser . ')'));

        // Achievements
        echo '<br/>' . $this->lipsoutput->display_h1(get_string('achievements', 'lips'));
    }
}

/**
 * Profile ranks page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile_ranks extends page_view {
    private $cmid;

    /**
     * page_profile_ranks constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile_ranks content
     */
    function display_content() {
        echo $this->lipsoutput->display_profile_menu('ranks') . '<br/>';

        echo 'Challenges';
    }
}

/**
 * Profile solved problems page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile_solved_problems extends page_view {
    private $cmid;

    /**
     * page_profile_solved_problems constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile_solved_problems content
     */
    function display_content() {
        echo $this->lipsoutput->display_profile_menu('solved_problems') . '<br/>';
        echo 'Solved problems';
    }
}

/**
 * Profile challenges page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaiïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile_challenges extends page_view {
    private $cmid;

    /**
     * page_profile_challenges constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile_challenges content
     */
    function display_content() {
        global $USER;

        require_once(dirname(__FILE__) . '/challenges_table.php');
        require_once(dirname(__FILE__) . '/mod_lips_challenges_search_form.php');

        echo $this->lipsoutput->display_profile_menu('challenges') . '<br/>';

        // User details
        $iduser = optional_param('id_user', null, PARAM_TEXT);
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));

        // Search form
        if ($iduser == null) {
            $searchForm = new mod_lips_challenges_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'challenges')), null, 'post', '', array('class' => 'search-form'));
        } else {
            $searchForm = new mod_lips_challenges_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'challenges', 'id_user' => $iduser)), null, 'post', '', array('class' => 'search-form'));
        }
        $searchForm->display();

        // Search result
        $search = new stdClass();
        if ($searchForm->is_submitted()) {
            $data = $searchForm->get_submitted_data();
            if (!empty($data->problemInputSearch)) {
                $search->problem = $data->problemInputSearch;
            }
            if (!empty($data->authorInputSearch)) {
                $search->author = $data->authorInputSearch;
            }
        }

        // Received challenges table
        echo $this->lipsoutput->display_h1(get_string('received_challenges', 'lips'));

        if ($iduser == null || $iduser == $userdetails->id) {
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
            $receivedchallengestable = new challenges_table($this->cm, $userdetails->id, true, $search, true);
        } else {
            $receivedchallengestable = new challenges_table($this->cm, $iduser, false, $search, true);
        }
        $receivedchallengestable->out("challenges_table", true);

        // Sent challenges table

        echo '<br/>' . $this->lipsoutput->display_h1(get_string('sent_challenges', 'lips'));

        if ($iduser == null || $iduser == $userdetails->id) {
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
            $sentchallengestable = new challenges_table($this->cm, $userdetails->id, true, $search, false);
        } else {
            $sentchallengestable = new challenges_table($this->cm, $iduser, false, $search, false);
        }
        $sentchallengestable->out("challenges_table", true);
    }
}

/**
 * Profile followed users page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile_followed_users extends page_view {
    private $cmid;

    /**
     * page_profile_followed_users constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile_followed_users content
     */
    function display_content() {
        global $USER;

        require_once(dirname(__FILE__) . '/followed_users_table.php');
        require_once(dirname(__FILE__) . '/mod_lips_search_form.php');

        echo $this->lipsoutput->display_profile_menu('followed_users') . '<br/>';
        echo $this->lipsoutput->display_h1(get_string('followed_users', 'lips'));

        // User details
        $iduser = optional_param('id_user', null, PARAM_TEXT);
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));

        // Search form
        if ($iduser == null) {
            $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'followed_users')), null, 'post', '', array('class' => 'search-form'));
        } else {
            $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'followed_users', 'id_user' => $iduser)), null, 'post', '', array('class' => 'search-form'));
        }
        $searchForm->display();

        // Search result
        $search = null;
        if ($searchForm->is_submitted()) {
            $data = $searchForm->get_submitted_data();
            if (!empty($data->inputSearch)) {
                $search = $data->inputSearch;
            }
        }

        // Followed users table
        if ($iduser == null || $iduser == $userdetails->id) {
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));

            $table = new followed_users_table($this->cm, $userdetails->id, true);
        } else {
            $table = new followed_users_table($this->cm, $iduser, false);
        }
        $table->out(get_string('followed_users_table', 'lips'), true);
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
        global $USER;

        require_once(dirname(__FILE__) . '/problems_table.php');
        require_once(dirname(__FILE__) . '/mod_lips_search_form.php');

        // Category documentation
        $categorydetails = get_category_details($this->id);
        echo $this->lipsoutput->display_documentation($categorydetails);

        // Category name
        echo $this->lipsoutput->display_h1($categorydetails->category_name);

        // Search form
        $searchform = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'categoryId' => $this->id)), null, 'post', '', array('class' => 'search-form'));
        $searchform->display();

        $search = null;
        if ($searchform->is_submitted()) {
            $data = $searchform->get_submitted_data();
            if (!empty($data->inputSearch)) {
                $search = $data->inputSearch;
            }
        }

        if (has_role('administration')) {
            echo '<p><span style="color: red;">*</span> : ' . get_string('problem_owner', 'lips') . '.</p>';
            echo '<img src="images/' . get_string('picture_testing', 'lips') . '" width="16px" height="16px"/> : ' . get_string('problem_testing_picture', 'lips') . '.';
        }

        // Problems table
        $table = new problems_table($this->cm, $categorydetails->id, $search);
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
        if ($this->originaction != null) {
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv, 'action' => $this->originaction));
        } else {
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv));
        }
        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}

/**
 * Display a message of confirmation for the deletion of problems.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_delete_problems extends page_view {

    function  __construct($cm) {
        parent::__construct($cm, "deleteProblems");
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/mod_lips_problem_form.php');
        $message = "";
        $serializedcategories = optional_param("categories", null, PARAM_TEXT);
        $count = 0;
        foreach (unserialize($serializedcategories) as $category) {
            $count++;
            $message .= $this->lipsoutput->display_p($category);
        }
        if ($count > 1) {
            $title = $this->lipsoutput->display_h2(get_string('administration_delete_problems_confirmation', 'lips'));
        } else {
            $title = $this->lipsoutput->display_h2(get_string('administration_delete_problem_confirmation_msg', 'lips'));
        }
        $continueurl = new moodle_url('action.php', array('id' => $this->cm->id, 'categories' => $serializedcategories, 'action' => 'deleteProblems'));
        $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'problem_category_select_delete'));
        echo $this->lipsoutput->confirm($title . $message, $continueurl, $cancelurl);
    }
}


/**
 * Display a message of confirmation for the deletion of a problem.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_delete_problem extends page_view {
    private $id;
    private $originv;
    private $originaction;
    private $categoryid;

    function  __construct($cm, $id, $originv, $originaction, $categoryid = null) {
        parent::__construct($cm, "deleteProblem");
        $this->id = $id;
        $this->originv = $originv;
        $this->originaction = $originaction;
        $this->categoryid = $categoryid;
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        $details = get_problem_details($this->id);
        $message = $this->lipsoutput->display_h2(get_string('administration_delete_problem_confirmation', 'lips') . " " . $details[$this->id]->problem_label . " ?");

        $continueurl = new moodle_url('action.php', array('id' => $this->cm->id, 'action' => $this->view, 'originV' => $this->originv, 'originAction' => $this->originaction, 'problemId' => $this->id, 'categoryId' => $this->categoryid));
        if ($this->originaction != null) {
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv, 'action' => $this->originaction, 'categoryId' => $this->categoryid));
        } else {
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv, 'categoryId' => $this->categoryid));
        }
        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}


/**
 * Display solutions of a problem.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_solutions extends page_view {
    private $id;

    function  __construct($cm, $id) {
        parent::__construct($cm, "solutions");
        $this->id = $id;
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        require_once(dirname(__FILE__) . '/mod_lips_search_form.php');

        $details = get_problem_details($this->id);
        echo $this->lipsoutput->display_h2($details[$this->id]->problem_label);
        $author_link = $this->lipsoutput->action_link(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'profile', 'id_user' => $details[$this->id]->user_id)), ucfirst($details[$this->id]->firstname) . ' ' . ucfirst($details[$this->id]->lastname));
        $author = $this->lipsoutput->display_span(get_string("problem_author", "lips"), array("class" => "label_field_page_problem")) . " " . $author_link;
        echo $this->lipsoutput->display_p($author, array("class" => "field_page_problem"));
        $datecreation = $this->lipsoutput->display_span(get_string("problem_date_creation", "lips"), array("class" => "label_field_page_problem")) . " " . date("d/m/y", $details[$this->id]->problem_date);
        echo $this->lipsoutput->display_p($datecreation, array("class" => "field_page_problem"));
        $nbresolutions = $this->lipsoutput->display_span(get_string("problem_nb_resolutions", "lips"), array("class" => "label_field_page_problem")) . " " . $details[$this->id]->problem_resolutions . " / " . $details[$this->id]->problem_attempts . " " . get_string("attempts", "lips");
        echo $this->lipsoutput->display_p($nbresolutions, array("class" => "field_page_problem"));
        $difficulty = $this->lipsoutput->display_span(get_string("difficulty", "lips"), array("class" => "label_field_page_problem")) . " " . get_string($details[$this->id]->difficulty_label, "lips");
        echo $this->lipsoutput->display_p($difficulty, array("class" => "field_page_problem"));
        $prerequisite = $details[$this->id]->problem_preconditions;
        if (empty($prerequisite)) {
            $prerequisite = get_string("none", "lips");
        }
        $prerequisite = $this->lipsoutput->display_span(get_string("prerequisite", "lips"), array("class" => "label_field_page_problem")) . " " . $prerequisite;
        echo $this->lipsoutput->display_p($prerequisite, array("class" => "field_page_problem"));

        $searchform = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'problemId' => $this->id)), null, 'post', '', array('class' => 'search-form', 'style' => 'width: 40%'));
        $searchform->display();

        $search = null;
        if ($searchform->is_submitted()) {
            $data = $searchform->get_submitted_data();
            if (!empty($data->inputSearch)) {
                $search = $data->inputSearch;
            }
        }
        $solutions = get_solutions($this->id, $search);
        foreach ($solutions as $solution) {
            echo $this->lipsoutput->display_solution($solution);
        }
    }
}

/**
 * Display a problem.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_problem extends page_view {
    private $id;

    function  __construct($cm, $id) {
        parent::__construct($cm, "problem");

        $this->id = $id;
    }

    /**
     * Display the view
     */
    function display() {

        // Manage rights
        if (problem_exists(array('id' => $this->id))) {
            $details = get_problem_details($this->id);
            $categorydetails = get_category_details($details[$this->id]->problem_category_id);
            $lipsinstance = get_instance($categorydetails->id_language);
            if ($lipsinstance->instance_link != $this->cm->id) {
                redirect(new moodle_url('view.php', array('id' => $lipsinstance->instance_link, 'view' => 'problem', 'problemId' => $this->id)));
            }
        } else {
            redirect(new moodle_url('view.php', array('id' => $this->cm->id)));
        }

        parent::display_header();
        $this->display_content();
        parent::display_footer();
    }

    function display_content() {
        global $USER;
        require_once(dirname(__FILE__) . '/mod_lips_search_form.php');
        require_once(dirname(__FILE__) . '/mod_lips_problem_form.php');
        require_once(dirname(__FILE__) . '/lips_rest_interface_impl.php');
        // Problem details
        $lips = get_current_instance();
        $details = get_problem_details($this->id);
        $categorydetails = get_category_details($details[$this->id]->problem_category_id);

        // Redirect if not allowed to see this problem
        if ($details[$this->id]->problem_testing == 1 && $USER->id != $details[$this->id]->problem_creator_id) {
            redirect(new moodle_url('view.php', array('id' => $this->cm->id)));
        }

        if ($details[$this->id]->problem_testing == 1) {
            echo $this->lipsoutput->display_notification(get_string('problem_testing_info', 'lips'), 'INFO');
        }

        /*--------------------------------
         *   Right buttons
         *------------------------------*/

        // Challenge button
        $buttondefie = $this->lipsoutput->action_link(new moodle_url("#"), "Défier", null, array("class" => "lips-button", "id" => "challenge"));

        // Solutions button
        $buttonsolutions = "";
        if (nb_resolutions_problem($USER->id, $this->id) > 0 || is_author($this->id, $USER->id)) {
            $buttonsolutions = $this->lipsoutput->action_link(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => $this->view, 'view' => 'solutions', "problemId" => $this->id)), "Solutions", null, array("class" => "lips-button"));
        }

        // Modify & Delete button
        $buttonedit = "";
        $buttondelete = "";
        if (has_role("administration") && is_author($this->id, $USER->id)) {
            $buttonedit = $this->lipsoutput->action_link(new moodle_url(""), get_string("edit", "lips"), null, array("class" => "lips-button"));
            $buttondelete = $this->lipsoutput->action_link(new moodle_url(""), get_string("delete", "lips"), null, array("class" => "lips-button"));
        }

        /*--------------------------------
         *   Left informations
         *------------------------------*/

        // Category documentation
        echo $this->lipsoutput->display_documentation($categorydetails);

        // Problem title
        echo $this->lipsoutput->display_h2($details[$this->id]->problem_label);

        // Buttons
        echo $this->lipsoutput->display_div($buttondefie . $buttonsolutions . $buttonedit . $buttondelete, array("id" => "problem-right-buttons"));

        // Author
        $authorlink = $this->lipsoutput->action_link(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'profile', 'id_user' => $details[$this->id]->user_id)), ucfirst($details[$this->id]->firstname) . ' ' . ucfirst($details[$this->id]->lastname));
        echo $this->lipsoutput->display_problem_information(get_string("problem_author", "lips"), $authorlink);

        // Creation date
        echo $this->lipsoutput->display_problem_information(get_string("problem_date_creation", "lips"), format_date($details[$this->id]->problem_date, false));

        // Number of resolutions
        echo $this->lipsoutput->display_problem_information(get_string("problem_nb_resolutions", "lips"), $details[$this->id]->problem_resolutions . " / " . $details[$this->id]->problem_attempts . " " . get_string("attempts", "lips"));

        // Difficulty
        echo $this->lipsoutput->display_problem_information(get_string("difficulty", "lips"), get_string($details[$this->id]->difficulty_label, "lips"));

        // Prerequisite
        $prerequisite = $details[$this->id]->problem_preconditions;
        if (empty($prerequisite)) {
            $prerequisite = get_string("none", "lips");
        }
        echo $this->lipsoutput->display_problem_information(get_string("prerequisite", "lips"), $prerequisite);

        /*--------------------------------
         *   Core informations
         *------------------------------*/

        // Subject
        echo $this->lipsoutput->display_h3(get_string("subject", "lips"), array("style" => "margin-bottom: 10px;"), false);
        echo $this->lipsoutput->display_p($details[$this->id]->problem_statement);

        // Tips
        if (!empty($details[$this->id]->problem_tips)) {
            echo $this->lipsoutput->display_h3(get_string("tips", "lips"), array("style" => "margin-bottom: 10px;"), false);
            echo $this->lipsoutput->display_p($details[$this->id]->problem_tips);
        }

        // Unit tests
        $hastest = false;
        $unittests = get_displayable_unittests($details[$this->id]->problem_unit_tests);
        if (count($unittests[1]) > 0) {
            echo $this->lipsoutput->display_h3(get_string("administration_problem_create_code_unittest_label", "lips"), array("style" => "margin-bottom: 10px;"), false);

            foreach ($unittests[1] as $unittest) {
                $img = $this->lipsoutput->display_img(get_unitest_picture());
                echo $this->lipsoutput->display_p($img . $this->lipsoutput->display_span($unittest), array('class' => 'unit-test'));
                $hastest = true;
            }
        }

        // Answer
        echo $this->lipsoutput->display_h3(get_string("answer", "lips"), array("style" => "margin-bottom: 10px;"), false);
        // echo '<div id="answerEditor" class="ace">' . htmlspecialchars($details[$this->id]->problem_code) . '</div>';

        // echo '<br/><center><a href="#" class="lips-button">' . get_string('send_response', 'lips') . '</a></center>';

        $formanswer = new mod_lips_problems_resolve_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'problemId' => $this->id)), array('idproblem' => $this->id), 'get');
        if ($formanswer->is_submitted()) {
            $data = $formanswer->get_data();
            $languages = lips_rest_interface_impl::execute($data->problem_answer);
            if ($languages['status'] != 1) {
                echo $this->lipsoutput->display_notification($languages['error'], 'ERROR');
            } else if ($languages['output'] == "true") {
                echo $this->lipsoutput->display_notification("Felicitation problem resolu", 'SUCCESS');
            } else {
                echo $this->lipsoutput->display_notification("Solution non valide", 'ERROR');
            }
        }
        $formanswer->display();

        // Similar problems
        $similarproblems = get_similar_problems($this->id);
        if (count($similarproblems) > 0) {
            echo $this->lipsoutput->display_h3(get_string("similar_problems", "lips"), array("style" => "margin-bottom: 10px;"), false);

            foreach ($similarproblems as $similarproblem) {
                $problemdetails = get_problem_details($similarproblem->problem_similar_id);
                $problemlink = $this->lipsoutput->action_link(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'problem', 'problemId' => $similarproblem->problem_similar_id)), $problemdetails[$similarproblem->problem_similar_id]->problem_label);
                $creatorlink = $this->lipsoutput->action_link(new moodle_url("view.php", array('id' => $this->cm->id, 'view' => 'profile', 'id_user' => $problemdetails[$similarproblem->problem_similar_id]->user_id)), ucfirst($problemdetails[$similarproblem->problem_similar_id]->firstname) . ' ' . strtoupper($problemdetails[$similarproblem->problem_similar_id]->lastname));
                echo $this->lipsoutput->display_p($problemlink . ' ' . get_string('from', 'lips') . ' ' . $creatorlink);
            }
        }

        // Create ace
        $this->lipsoutput->display_ace_form('answerEditor', 'id_problem_answer', $lips->coloration_language, 'resolution');

        // Challenge dialog
        $userid = get_user_details(array("id_user_moodle" => $USER->id))->id;
        echo $this->lipsoutput->display_challenge_dialog($categorydetails->category_name, $details[$this->id]->problem_label, fetch_challenged_users($userid, $this->id));
        echo '<input type="hidden" id="hiddenLIPSid" value="' . $lips->id . '"/>';
        echo '<input type="hidden" id="hiddenProblemid" value="' . $this->id . '"/>';
    }
}

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
        $continueurl = new moodle_url('../../backup/restorefile.php', array('contextid'=>$coursecontext->id));
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
        $continueurl = new moodle_url('../../backup/backup.php', array('id'=>$coursecontext->instanceid, 'cm'=>$cm->id));
        
        $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => "administration"));

        $message = $this->lipsoutput->display_h2(get_string('administration_problems_export_confirmation', 'lips'));

        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}

/**
 * Page to view the global rank
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_rank extends page_view {

    /**
     * page_rank constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "rank");
    }

    /**
     * Display the page_rank content
     */
    function display_content() {
        global $CFG;
        require_once("$CFG->libdir/tablelib.php");
        require_once(dirname(__FILE__) . '/rank_table.php');
        require_once(dirname(__FILE__) . '/mod_lips_filter_form.php');

        // Rank title
        $category_id_post = optional_param("category_id_js", null, PARAM_TEXT);
        echo $this->lipsoutput->display_h1(get_string('Rank', 'lips'));
        $filterform = new mod_lips_filter_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view)), null, 'post', '', array('class' => 'search-form'));
        $usersearch = null;
        $instance_id_js = null;
        $category_id_js = null;
        if ($filterform->is_submitted()) {
            $data = $filterform->get_submitted_data();
            if (isset($data->userSearch) && !empty($data->userSearch)) {
                $usersearch = $data->userSearch;
            }
            if (isset($data->language_id_js) && $data->language_id_js != "all") {
                $instance_id_js = $data->language_id_js;
            }
            if (isset($category_id_post) && $category_id_post != "all") {
                $category_id_js = $category_id_post;
            }
        }
        $table = new rank_table($this->cm, $usersearch, $instance_id_js, $category_id_js);
        $filterform->display();
        $table->out(10, true);
    }
}