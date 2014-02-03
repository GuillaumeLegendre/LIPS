<?php
/**
 * Created by PhpStorm.
 * User: Mickael
 * Date: 11/01/14
 * Time: 22:56
 */
require_once($CFG->dirroot . '/tag/lib.php');
require_once($CFG->dirroot . '/mod/lips/locallib.php');

abstract class page_view  {

    protected $cm;
    protected $view;
    protected $lipsoutput;

    function __construct($cm,$view) {
        global $PAGE;
        $this->cm=$cm;
        $this->view=$view;
        $this->lipsoutput = $PAGE->get_renderer('mod_lips');
    }

    function displayHeader() {
        global $OUTPUT;
        echo $OUTPUT->header();
        echo $this->lipsoutput->tabs($this->view);
    }

    function display() {
        $this->displayHeader();
        $this->display_content();
        $this->displayFooter();
    }

    abstract protected function display_content();

    function displayFooter() {
        global $OUTPUT;
        echo $OUTPUT->footer();
    }
}

class page_list_categories extends page_view {

    function  __construct($cm) {
        parent::__construct($cm,"problems");
    }

    function display_content() {
        global $CFG;
        require_once "$CFG->libdir/tablelib.php";
        require_once(dirname(__FILE__).'/categories_table.php');
        $table=new categories_table("mdl_lips_category");
        $table->set_sql("*","mdl_lips_category","1");
        $table->define_baseurl("http://localhost/moodle/mod/lips/view.php?id=6&view=problems");
        $table->define_headers(array("Catégorie","Documentation",""));
        $table->define_columns(array("category_name","category_documentation","actions"));
        $table->sortable(true);
        $table->out(10,true);
    }
}

class page_index extends page_view {

    function  __construct($cm) {
        parent::__construct($cm,"index");
    }

    function display_content() {
        global $USER;
        echo ("<h1>Bienvenue ".$USER->username."</h1>");
    }
}


class page_admin extends page_view {
    function  __construct($cm) {
        parent::__construct($cm,"administration");
    }

    function display_content() {
        global $CFG;

        // Form classes
        require_once(dirname(__FILE__) . '/mod_lips_configure_form.php');

        echo '<h1>Administration</h1>';

        // Configure language
        echo '<h2>' . get_string('administration_language_configure_title', 'lips') . '</h2>';
        echo '<p>' . get_string('administration_language_configure_msg', 'lips') . '</p>';

        $configureLanguageForm = new mod_lips_configure_language_form("test.html", null, 'post');
        $configureLanguageForm->display();

        // Modify language picture
        echo '<h2>' . get_string('administration_language_image_title', 'lips') . '</h2>';
        echo '<p>' . get_string('administration_language_image_msg', 'lips') . '</p>';
        echo '<center><img src="' . get_language_picture() . '" width="64px" height="64px"/></center>';

        $configurePictureForm = new mod_lips_configure_picture_form("test.html", null, 'post');
        $configurePictureForm->display();

        // Language base code
        echo '<h2>' . get_string('administration_language_code_title', 'lips') . '</h2>';
        echo '<p>' . get_string('administration_language_code_msg', 'lips') . '</p>';

        $configureCodeForm = new mod_lips_configure_code_form("test.html", null, 'post');
        $configureCodeForm->display();
    }
}


class page_users extends page_view {
    function  __construct($cm) {
        parent::__construct($cm,"users");
    }

    function display_content() {
        global $CFG,$OUTPUT;
        require "$CFG->libdir/tablelib.php";
        $table=new table_sql("mdl_lips_user");
        $table->set_sql("*","mdl_lips_user","1");
        $table->define_baseurl("http://localhost/moodle/mod/lips/view.php?id=6&view=problems");
        $table->define_headers(array("Nom","Prenom"));
        $table->define_columns(array("user_name","user_first_name"));
        $table->sortable(true);
        $table->out(10,true);
    }

}

class page_profil extends page_view {

    private $cmId;

    function  __construct($cm,$cmId) {
        parent::__construct($cm,"profil");
        $this->cmId=$cmId;
    }

    function display_content() {
        global $OUTPUT,$USER,$DB;
        $id=$DB->get_record('course', array('id' => $this->cm->course), '*', MUST_EXIST);;
        $avatar = new user_picture($USER);
        $avatar->courseid = $this->cmId;
        $avatar->link = true;
        echo $OUTPUT->render($avatar)." ";
        echo $USER->firstname." ".$USER->lastname;
    }

}