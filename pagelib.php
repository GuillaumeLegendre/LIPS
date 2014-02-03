<?php
/**
 * Created by PhpStorm.
 * User: Mickael
 * Date: 11/01/14
 * Time: 22:56
 */
require_once($CFG->dirroot . '/tag/lib.php');

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
        echo "<h1>Administration</h1>";
        require_once(dirname(__FILE__).'/administration_form.php');
        $mform = new mod_lips_administration_form("http://localhost/moodle/mod/lips/view.php?id=6&view=administration", null,
            'post');
        /* Donne le focus au premier élément du formulaire. */
        $mform->focus();
        //Form processing and displaying is done here
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
        }
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