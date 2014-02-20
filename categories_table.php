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

/**
 * Categories table
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class categories_table extends table_sql {
    private $cm;

    public function  __construct($cm, $search = null) {
        global $USER;
        parent::__construct("mdl_lips_category");
        $this->cm = $cm;

        $this->set_sql("mlc.id, category_name, category_documentation, COUNT(mlp.id) AS category_problems",
            "mdl_lips_category mlc LEFT JOIN mdl_lips_problem mlp ON mlc.id = mlp.problem_category_id
            AND (problem_testing = 0 OR problem_testing = 1 AND problem_creator_id = " . $USER->id . ")",
            "mlc.id_language = " . get_current_instance()->id . "
            GROUP BY mlc.id HAVING COUNT(mlc.id) > 0
            ORDER BY category_name ASC");
        $this->set_count_sql("SELECT COUNT(*) FROM mdl_lips_category WHERE id_language = " . get_current_instance()->id);
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

    public function other_cols($colname, $attempt) {
        global $OUTPUT, $PAGE;

        if ($colname == "category_name") {
            $url = new action_link(
                new moodle_url('view.php', array(
                    'id' => $this->cm->id,
                    'view' => 'category',
                    'categoryId' => $attempt->id)),
                $attempt->category_name);

            return $OUTPUT->render($url);
        }

        if ($colname == "actions") {
            $context = context_module::instance($this->cm->id);
            $a = "";

            if (has_capability('mod/lips:administration', $context)) {
                $a = $OUTPUT->action_icon(new moodle_url("view.php", array('id' => $this->cm->id
                    , 'view' => 'administration', 'action' => 'category_modify', 'category_id' => $attempt->id))
                    , new pix_icon("t/edit", "edit"));
                if (is_removable($attempt->id, get_current_instance()->id)) {
                    $a .= " " . $OUTPUT->action_icon(new moodle_url("view.php",
                                array('id' => $PAGE->cm->id,
                                    'view' => 'deleteCategory', 'categoryId' => $attempt->id, 'originV' => 'problems')),
                            new pix_icon("t/delete", "delete"));
                }
            }

            $category = get_category_details($attempt->id);
            if ($category->category_documentation_type == 'LINK') {
                $a .= " " . $OUTPUT->action_icon($category->category_documentation, new pix_icon("i/manual_item", "documentation"));
            } else if ($category->category_documentation_type == 'TEXT') {
                $a .= " " . $OUTPUT->action_icon(new moodle_url("view.php",
                            array('id' => $PAGE->cm->id, 'view' => 'categoryDocumentation',
                                'categoryId' => $attempt->id)),
                        new pix_icon("i/manual_item", "documentation"));
            }

            return $a;
        }

        return null;
    }
} 