<?php
global $CFG;
require_once("$CFG->libdir/tablelib.php");
require_once("$CFG->libdir/outputrenderers.php");


class categories_table extends table_sql {
    private $cm;

    function  __construct($cm) {
        parent::__construct("mdl_lips_category");
        $this->cm = $cm;
        $this->set_sql("mlc.id, category_name, category_documentation, count(mlp.id) AS category_problems", "mdl_lips_category mlc LEFT JOIN mdl_lips_problem mlp ON mlc.id = mlp.problem_category_id" , "mlc.id_language=".get_current_instance()->id." GROUP BY mlc.id HAVING COUNT( mlc.id ) >0");
        $this->set_count_sql("SELECT COUNT(*) FROM mdl_lips_category mlc LEFT JOIN mdl_lips_problem mlp ON mlc.id = mlp.problem_category_id");
        $this->define_baseurl(new moodle_url('view.php', array('id' => $cm->id, 'view' => "problems")));
        $context = context_module::instance($cm->id);
        if (has_capability('mod/lips:administration', $context)) {
            $this->define_headers(array(get_string('category', 'lips'), get_string('number_of_problems', 'lips'), ""));
            $this->define_columns(array("category_name", "category_problems", "actions"));
        } else {
            $this->define_headers(array(get_string('category', 'lips'), get_string('number_of_problems', 'lips')));
            $this->define_columns(array("category_name", "category_problems"));
        }
        $this->sortable(true);
    }

    function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE;
        if ($colname == "category_name") {
            $url = new action_link(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => 'category', 'categoryId' => $attempt->id)), $attempt->category_name);
            return $OUTPUT->render($url);
        }

        if ($colname == "actions") {
            $context = context_module::instance($this->cm->id);
            $a="";
            if (has_capability('mod/lips:administration', $context)) {
                $a=$OUTPUT->action_icon(new moodle_url("action.php", array('id' => $this->cm->id, 'action' => 'editCategory', 'categoryId' => $attempt->id, "originV" => "problems")), new pix_icon("t/edit", "edit"));
                //$a.=" " . $OUTPUT->action_icon(new moodle_url("action.php", array('id' => $PAGE->cm->id, 'action' => 'deleteCategory', 'categoryId' => $attempt->id, "originV" => "problems")), new pix_icon("t/delete", "delete"));
                $a.=" " . $OUTPUT->action_icon(new moodle_url("view.php", array('id' => $PAGE->cm->id, 'view' => 'deleteCategory', 'originV' => 'problems', 'categoryId' => $attempt->id)), new pix_icon("t/delete", "delete"));
            }
            return $a.=" " . $OUTPUT->action_icon(new moodle_url("view.php", array('id' => $PAGE->cm->id, 'view' => 'categoryDocumentation', 'categoryId' => $attempt->id)), new pix_icon("t/manual_item", "documentation"));
        }
        return null;
    }
} 