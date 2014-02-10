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

global $CFG;

require_once("$CFG->libdir/tablelib.php");
require_once("$CFG->libdir/outputrenderers.php");


class problems_table extends table_sql {

    private $cm;

    function  __construct($cm, $id) {
        parent::__construct("mdl_lips_problem");
        $this->cm = $cm;
        $this->set_sql("mlp.id,problem_label,problem_date,problem_creator_id,problem_attempts, difficulty_label", "mdl_lips_problem mlp join mdl_lips_difficulty mld on problem_difficulty_id=mld.id", "problem_category_id=" . $id);
        $this->define_baseurl(new moodle_url('view.php', array('id' => $cm->id, 'view' => 'category', "categoryId" => $id)));


        $context = context_module::instance($cm->id);
        if (has_capability('mod/lips:administration', $context)) {
            $this->define_headers(array("Problème", "Difficulté", "Date", "Rédacteur", "Nombre de résolutions", ""));
            $this->define_columns(array("problem_label", "difficulty_label", "problem_date", "problem_creator_id", "problem_attempts", "actions"));
        } else {
            $this->define_headers(array("Problème", "Difficulté", "Date", "Rédacteur", "Nombre de résolutions"));
            $this->define_columns(array("problem_label", "difficulty_label", "problem_date", "problem_creator_id", "problem_attempts"));
        }
        $this->sortable(true);
    }

    function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE, $USER;
        if ($colname == "problem_date") {
            return date('d/m/Y', $attempt->problem_date);
        } else if ($colname == "difficulty_label") {
            return get_string($attempt->difficulty_label, "lips");
        } else if ($colname == "actions") {
            $context = context_module::instance($this->cm->id);
            $a = "";
            if (has_capability('mod/lips:administration', $context) && is_author($attempt->id, $USER->id)) {
                $a = $OUTPUT->action_icon(new moodle_url("action.php", array('id' => $this->cm->id, 'action' => 'editProblem', 'problemId' => $attempt->id, "originV" => "problems")), new pix_icon("t/edit", "edit"));
                $a .= " " . $OUTPUT->action_icon(new moodle_url("view.php", array('id' => $PAGE->cm->id, 'view' => 'deleteProblem', 'problemId' => $attempt->id)), new pix_icon("t/delete", "delete"));
            }
            return $a;
        }
        return null;
    }
}