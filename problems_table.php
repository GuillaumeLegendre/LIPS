<?php

global $CFG;

require_once("$CFG->libdir/tablelib.php");
require_once("$CFG->libdir/outputrenderers.php");


class problems_table extends table_sql
{

    function  __construct() {
        global $PAGE;
        parent::__construct("mdl_lips_problem");
        $this->set_sql("*", "mdl_lips_problem", "1");
        $this->define_baseurl(new moodle_url('view.php', array('id' => $PAGE->cm->id, 'view' => 'category')));
		$this->define_headers(array(get_string('problem', 'lips'), get_string('level', 'lips'), get_string('date', 'lips'), get_string('author', 'lips'), get_string('number_of_resolutions', 'lips'), get_string('resolved', 'lips')));
        $this->define_columns(array("Problème", "Difficulté", "Date", "Rédacteur", "Nombre de résolutions", "Résolu"));
        $this->sortable(true);
    }

    function other_cols($colname, $attempt) {


        return null;
    }
}