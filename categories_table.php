<?php
/**
 * Created by PhpStorm.
 * User: mickael
 * Date: 07/01/14
 * Time: 20:24
 */
global $CFG;
require_once "$CFG->libdir/tablelib.php";
require_once "$CFG->libdir/outputrenderers.php";


class categories_table extends table_sql
{

    function  __construct(){
        global $PAGE;
        parent::__construct("mdl_lips_problem");
        $this->set_sql("*", "mdl_lips_category", "1");
        $this->define_baseurl(new moodle_url('view.php', array('id' => $PAGE->cm->id, 'view' => "problems")));
        $this->define_headers(array("Catégorie", "Documentation", ""));
        $this->define_columns(array("category_name", "category_documentation", "actions"));
        $this->sortable(true);
    }

    function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE;
        if ($colname == "category_name") {
            $url = new action_link(new moodle_url('view.php', array('id' => $PAGE->cm->id, 'view' => 'category', 'categoryId' => $attempt->id)), $attempt->category_name);
            return $OUTPUT->render($url);
        }

        if ($colname == "actions") {
            return $OUTPUT->action_icon(new moodle_url("action.php", array('id' => $PAGE->cm->id, 'action' => 'editCategory', 'categoryId' => $attempt->id)), new pix_icon("t/edit", "edit")) . " " . $OUTPUT->action_icon(new moodle_url("action.php", array('id' => $PAGE->cm->id, 'action' => 'deleteCategory', 'categoryId' => $attempt->id)), new pix_icon("t/delete", "delete"));
        }
        return null;
    }
} 