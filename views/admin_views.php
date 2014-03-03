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
        global $CFG, $PAGE;

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
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
        require_once(dirname(__FILE__) . '/../form/mod_lips_configure_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Configure language.
        echo $this->lipsoutput->display_h2(get_string('administration_language_configure_title', 'lips'));
        echo $this->lipsoutput->display_p(get_string('administration_language_configure_msg', 'lips'));
        $activelanguages = get_active_languages();
        $currentlanguage = get_current_instance()->compile_language;
        $advmsg = "<br/>";
        foreach ($activelanguages as $language) {
            if ($language->compile_language != $currentlanguage) {
                $advmsg .= "<strong>" . $language->compile_language . "</strong><br/>";
            }
        }
        if ($advmsg != "<br/>") {
            echo $PAGE->get_renderer('mod_lips')->display_notification(
                get_string('administration_warning_existing_language', 'lips') . $advmsg, 'WARNING');
        }

        $lips = get_current_instance();
        if (count_languages_number($lips->id) > 0) {
            echo $PAGE->get_renderer('mod_lips')->display_notification(
                get_string('administration_existing_problems', 'lips'), 'ERROR');
        }

        $configurelanguageform = new mod_lips_configure_language_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_configure')),
            null, 'post');

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
        require_once(dirname(__FILE__) . '/../form/mod_lips_configure_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        $configurepictureform = new mod_lips_configure_picture_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_picture')),
            null, 'post');
        if ($configurepictureform->is_submitted()) {
            $configurepictureform->handle();
        }

        // Modify language picture.
        echo $this->lipsoutput->display_h2(get_string('administration_language_image_title', 'lips'), array('id' => 'picture'));
        echo $this->lipsoutput->display_p(get_string('administration_language_image_msg', 'lips'));
        echo '<center>' . $this->lipsoutput->display_img(
                get_language_picture(), array('width' => '64px', 'height' => '64px')) . '</center>';

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
        require_once(dirname(__FILE__) . '/../form/mod_lips_configure_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Language base code.
        echo $this->lipsoutput->display_h2(get_string('administration_language_code_title', 'lips'), array('id' => 'code'));
        echo $this->lipsoutput->display_p(get_string('administration_language_code_msg', 'lips'));

        $lips = get_current_instance();
        if ($lips->compile_language == null && has_role('administration')) {
            echo $this->lipsoutput->display_notification(get_string('administration_no_compile_language', 'lips'), 'ERROR');
        }
        if ($lips->coloration_language == null && has_role('administration')) {
            echo $this->lipsoutput->display_notification(get_string('administration_no_syntax_highlighting', 'lips'), 'WARNING');
        }

        $lips = get_current_instance();
        $configurecodeform = new mod_lips_configure_code_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_base')),
            (array)$lips, 'post');

        if ($configurecodeform->is_submitted()) {
            $configurecodeform->handle();

            // Do no remove. Used to refresh the Ace code with the updated data.
            $lips = get_current_instance();
            $configurecodeform = new mod_lips_configure_code_form(
                new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'language_base')),
                (array)$lips, 'post');
            $configurecodeform->display();
        } else {
            $configurecodeform->display();
        }

        // Create ace.
        $this->lipsoutput->display_ace_form('configEditor', 'id_areaBaseCode', $lips->coloration_language, 'configure');
    }
}

/**
 * Achievement administration
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_achievement_select extends page_view {

    /**
     * page_admin_achievement_select constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the page_admin_achievement_select content
     */
    function display_content() {
        global $CFG;

        require_once(dirname(__FILE__) . '/../form/mod_lips_achievement_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Modify a category.
        echo $this->lipsoutput->display_h2(get_string('administration_achievement_title', 'lips'));

        $modifyselectachievementform = new mod_lips_achievement_select_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'achievement')),
            null, 'post');
        $modifyselectachievementform->display();
    }
}

/**
 * Achievement administration
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_admin_achievement extends page_view {

    /**
     * page_admin_achievement constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "administration");
    }

    /**
     * Display the view
     */
    function display() {
        global $CFG;
        require_once(dirname(__FILE__) . '/../form/mod_lips_achievement_form.php');

        $modifyAchievementForm = new mod_lips_achievement_form();

        if ((!isset($_POST['selectAchievement']) ||
                !isset($_POST['selectAchievement'][0]) ||
                !isset($_POST['selectAchievement'][1])) &&
            !$modifyAchievementForm->is_submitted()
        ) {
            redirect(new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'achievement_select')));
        }

        parent::display_header();
        $this->display_content();
        parent::display_footer();
    }

    /**
     * Display the page_admin_achievement content
     */
    function display_content() {

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Modify a category.
        echo $this->lipsoutput->display_h2(get_string('administration_achievement_title', 'lips'));

        $modifyachievementform = new mod_lips_achievement_form();
        if ($modifyachievementform->is_submitted()) {
            $modifyachievementform = new mod_lips_achievement_form(
                new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'achievement')),
                null, 'post');

            $modifyachievementform->handle();
            $modifyachievementform->display();
        } else {
            $achievement = get_achievement_details(array('id' => $_POST['selectAchievement'][1]));
            $modifyachievementform = new mod_lips_achievement_form(
                new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => 'administration', 'action' => 'achievement')),
                (array)$achievement, 'post');
            $modifyachievementform->display();
        }
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
        require_once(dirname(__FILE__) . '/../form/mod_lips_category_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Create a category.
        echo $this->lipsoutput->display_h2(get_string('administration_category_create_title', 'lips'));
        echo $this->lipsoutput->display_p(get_string('administration_category_msg', 'lips'));

        $createcategoryform = new mod_lips_category_create_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_create')),
            null, 'post');

        if ($createcategoryform->is_submitted()) {
            $createcategoryform->handle();
            $createcategoryform->display();
        } else {
            $createcategoryform->display();
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
        require_once(dirname(__FILE__) . '/../form/mod_lips_category_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Modify a category.
        echo $this->lipsoutput->display_h2(get_string('administration_category_modify_title', 'lips'));

        $modifyselectcategoryform = new mod_lips_category_modify_select_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')),
            null, 'post');

        if ($modifyselectcategoryform->is_submitted()) {
            $modifyselectcategoryform->handle();
            $modifyselectcategoryform->display();
        } else {
            $modifyselectcategoryform->display();
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
        require_once(dirname(__FILE__) . '/../form/mod_lips_category_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Modify a category.
        echo $this->lipsoutput->display_h2(get_string('administration_category_modify_title', 'lips'));
        echo $this->lipsoutput->display_p(get_string('administration_category_msg', 'lips'));

        $modifycategoryform = new mod_lips_category_modify_form();

        if ($modifycategoryform->is_submitted()) {
            $modifycategoryform = new mod_lips_category_modify_form(
                new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')),
                null, 'post');

            $modifycategoryform->handle();
            $modifycategoryform->display();
        } else {
            $categoryid = optional_param('category_id', null, PARAM_INT);

            if ($categoryid == null) {
                $modifyselectcategoryform = new mod_lips_category_modify_select_form();
                $data = $modifyselectcategoryform->get_submitted_data();
                $categorydetails = get_category_details($data->selectCategory);

                $modifycategoryform = new mod_lips_category_modify_form(
                    new moodle_url('view.php',
                        array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')),
                    (array)$categorydetails, 'post');

                $modifycategoryform->display();
            } else {
                $categorydetails = get_category_details($categoryid);

                $modifycategoryform = new mod_lips_category_modify_form(
                    new moodle_url('view.php',
                        array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'category_modify')),
                    (array)$categorydetails, 'post');

                $modifycategoryform->display();
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
        require_once(dirname(__FILE__) . '/../form/mod_lips_category_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Delete a category.
        echo $this->lipsoutput->display_h2(get_string('administration_category_delete_title', 'lips'));

        $deletecategoryform = new mod_lips_category_delete_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id,
                    'view' => 'deleteCategory',
                    'originV' => $this->view,
                    'originAction' => 'category_delete')),
            null, 'post');
        $deletecategoryform->display();
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
     * Display the page_admin_problem_modify content
     */
    function display_content() {
        global $CFG;
        require_once(dirname(__FILE__) . '/../form/mod_lips_problem_form.php');

        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        echo $this->lipsoutput->display_administration_menu();
        echo $this->lipsoutput->display_h2(get_string('administration_problem_modify_title', 'lips'));

        $modifyproblemform = new mod_lips_problem_modify_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problem_modify', 'problemId' => $this->id)),
            null, "post");
        if ($modifyproblemform->is_submitted()) {
            $modifyproblemform->handle($this->cm->instance);
        }
        $problemdetails = get_problem_details($this->id);
        $modifyproblemform = new mod_lips_problem_modify_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problem_modify', 'problemId' => $this->id)),
            (array)$problemdetails[$this->id], 'post', '', array('class' => 'problem-form'));

        $modifyproblemform->display();

        $lips = get_current_instance();

        // Create ace.
        $this->lipsoutput->display_ace_form('preconfigEditor', '', $lips->coloration_language, 'readonly');
        $this->lipsoutput->display_ace_form('importsEditor', 'id_problem_imports', $lips->coloration_language, '');
        $this->lipsoutput->display_ace_form('problemCodeEditor', 'id_problem_code',
            $lips->coloration_language, 'code', 'eclipse', addslashes($lips->comment_format));
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
        require_once(dirname(__FILE__) . '/../form/mod_lips_problem_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Modify a problem.
        echo $this->lipsoutput->display_h2(get_string('administration_problem_modify_title', 'lips'));

        $modifyselectproblemform = new mod_lips_problem_modify_select_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problem_modify')),
            null, '');
        $modifyselectproblemform->display();
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
        require_once(dirname(__FILE__) . '/../form/mod_lips_category_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Modify a problem.
        echo $this->lipsoutput->display_h2(get_string('administration_problem_delete_title', 'lips'));

        $lips = get_current_instance();
        if (count(fetch_all_categories($lips->id)) > 0) {
            $modifyselectcategoryform = new mod_lips_category_select_problems_delete_form(
                new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problems_delete')),
                null, 'get');

            if ($modifyselectcategoryform->is_submitted()) {
                $modifyselectcategoryform->handle();
                $modifyselectcategoryform->display();
            } else {
                $modifyselectcategoryform->display();
            }
        } else {
            echo $this->lipsoutput->display_notification(get_string('administration_empty_problems', 'lips'), 'INFO');
            echo '<br/><br/><br/><br/>';
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
        require_once(dirname(__FILE__) . '/../form/mod_lips_problem_form.php');
        $deleteproblemform = new mod_lips_problems_delete_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id,
                    'view' => 'administration',
                    'action' => 'problems_delete',
                    'idcategory' => $this->idcategory)),
            array('idcategory' => $this->idcategory), 'post');
        if ($deleteproblemform->is_submitted()) {
            $problemsid = array();
            foreach ($deleteproblemform->get_data() as $problem => $state) {
                if ($state == 1) {
                    $problemsid[] = $problem;
                }
            }
            redirect(new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => 'deleteProblems', 'idproblems' => serialize($problemsid))));
            return;
        }
        parent::display_header();
        $this->display_content();
        $deleteproblemform->display();
        parent::display_footer();
    }

    /**
     * Display the page_admin_problem_delete content
     */
    function display_content() {
        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // Delete a problem.
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
        require_once(dirname(__FILE__) . '/../form/mod_lips_problem_form.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu;
        echo $this->lipsoutput->display_administration_menu();

        // Create a problem.
        echo $this->lipsoutput->display_h2(get_string('administration_problem_create_title', 'lips'));

        $lips = get_current_instance();
        if (count(fetch_all_categories($lips->id)) == 0) {
            echo $this->lipsoutput->display_notification(get_string('administration_empty_category_msg', 'lips'), 'INFO');
            echo '<br/><br/><br/><br/>';
        } else {
            if ($lips->compile_language == null && has_role('administration')) {
                echo $this->lipsoutput->display_notification(get_string('administration_no_compile_language', 'lips'), 'ERROR');
            }
            if ($lips->coloration_language == null && has_role('administration')) {
                echo $this->lipsoutput->display_notification(
                    get_string('administration_no_syntax_highlighting', 'lips'), 'WARNING');
            }

            $createproblemform = new mod_lips_problem_create_form(
                new moodle_url('view.php',
                    array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'problem_create')),
                null, 'post', '', array('class' => 'problem-form'));

            if ($createproblemform->is_submitted()) {
                $createproblemform->handle();
                $createproblemform->display();
            } else {
                $createproblemform->display();
            }
        }

        // Create ace.
        $this->lipsoutput->display_ace_form('preconfigEditor', '', $lips->coloration_language, 'readonly');
        $this->lipsoutput->display_ace_form('importsEditor', 'id_problem_imports', $lips->coloration_language, '');
        $this->lipsoutput->display_ace_form('problemCodeEditor', 'id_problem_code',
            $lips->coloration_language, 'code', 'eclipse', addslashes($lips->comment_format));
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

        require_once(dirname(__FILE__) . '/../tables/my_problems_table.php');

        // Administration title.
        echo $this->lipsoutput->display_h1(get_string('administration', 'lips'));

        // Administration menu.
        echo $this->lipsoutput->display_administration_menu();

        // My problems.
        echo $this->lipsoutput->display_h2(get_string('administration_my_problems_title', 'lips'));

        $lips = get_current_instance();
        $table = new my_problems_table($this->cm, $lips->id, $USER->id);
        $table->out(get_string('my_problems_table', 'lips'), true);

        echo '<br/><br/><br/><br/><br/>';
    }
}
