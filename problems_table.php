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

    function  __construct($cm, $id, $search = null) {
        parent::__construct("mdl_lips_problem");
        $this->cm = $cm;

        if ($search != null) {
            $this->set_sql("mlp.id,problem_label,problem_date,problem_creator_id, difficulty_label, count(mls.id) as problem_resolutions, firstname, lastname, mlu.id AS user_id",
                "mdl_lips_problem mlp join mdl_lips_difficulty mld on problem_difficulty_id=mld.id left join mdl_lips_problem_solved mls on mls.problem_solved_problem=mlp.id join mdl_user mu on mu.id=problem_creator_id JOIN mdl_lips_user mlu ON mlu.id_user_moodle = problem_creator_id",
                "problem_category_id=" . $id . " and problem_label like '%$search%' GROUP BY mlp.id");
        } else {
            $this->set_sql("mlp.id,problem_label,problem_date,problem_creator_id, difficulty_label, count(mls.id) as problem_resolutions, firstname, lastname, mlu.id AS user_id",
                "mdl_lips_problem mlp join mdl_lips_difficulty mld on problem_difficulty_id=mld.id left join mdl_lips_problem_solved mls on mls.problem_solved_problem=mlp.id join mdl_user mu on mu.id=problem_creator_id JOIN mdl_lips_user mlu ON mlu.id_user_moodle = problem_creator_id",
                "problem_category_id=" . $id . " GROUP BY mlp.id");
        }
        $this->define_baseurl(new moodle_url('view.php', array('id' => $cm->id, 'view' => 'category', "categoryId" => $id)));
        $this->set_count_sql("SELECT COUNT(*) FROM mdl_lips_problem where problem_category_id=" . $id);
        $context = context_module::instance($cm->id);
        if (has_capability('mod/lips:administration', $context)) {
            $this->define_headers(array("Problème", "Difficulté", "Date", "Rédacteur", "Nombre de résolutions", ""));
            $this->define_columns(array("problem_label", "difficulty_label", "problem_date", "problem_creator_id", "problem_resolutions", "actions"));
        } else {
            $this->define_headers(array("Problème", "Difficulté", "Date", "Rédacteur", "Nombre de résolutions"));
            $this->define_columns(array("problem_label", "difficulty_label", "problem_date", "problem_creator_id", "problem_resolutions"));
        }
        $this->sortable(true);
    }

    function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE, $USER;
        if ($colname == "problem_label") {
            $url = new action_link(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => 'problem', 'problemId' => $attempt->id)), $attempt->problem_label);
            return $OUTPUT->render($url);
        } else if ($colname == "problem_date") {
            return date('d/m/Y', $attempt->problem_date);
        } else if ($colname == "difficulty_label") {
            return get_string($attempt->difficulty_label, "lips");
        } else if ($colname == "actions") {
            $context = context_module::instance($this->cm->id);
            $a = "";
            if (has_capability('mod/lips:administration', $context) && is_author($attempt->id, $USER->id)) {
                $a = $OUTPUT->action_icon(new moodle_url("action.php", array('id' => $this->cm->id, 'action' => 'editProblem', 'problemId' => $attempt->id, "originV" => "problems")), new pix_icon("t/edit", "edit"));
                $a .= " " . $OUTPUT->action_icon(new moodle_url("view.php", array('id' => $PAGE->cm->id, 'view' => 'deleteProblem', 'problemId' => $attempt->id, 'originV' => "category", "categoryId" => $attempt->id)), new pix_icon("t/delete", "delete"));
            }
            return $a;
        } else if ($colname == "problem_creator_id") {
            return $OUTPUT->action_link(new moodle_url("view.php", array('id' => $PAGE->cm->id, 'view' => 'profile', 'id_user' => $attempt->user_id)), ucfirst($attempt->firstname) . ' ' . strtoupper($attempt->lastname));
        }
        return null;
    }
}