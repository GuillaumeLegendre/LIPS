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
 * Structure step to restore one lips activity
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_lips_activity_structure_step extends restore_activity_structure_step {

    // The current lips instance id.
    private $lipsid;
    private $newproblemsidarray = array();

    /**
     * Define the structure of the tree to restore.
     */
    protected function define_structure() {
 
        $paths = array();
 
        $paths[] = new restore_path_element('lips', '/activity/lips');
        $paths[] = new restore_path_element('lips_category', '/activity/lips/categories/category');
        $paths[] = new restore_path_element('lips_problem', '/activity/lips/categories/category/problems/problem');
        $paths[] = new restore_path_element('lips_difficulty', '/activity/lips/categories/category/problems/problem/difficulties/difficulty');
        
        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }
 
    /**
     * Restore the lips item if it doesn't already exist.
     */
    protected function process_lips($data) {
        global $DB, $PAGE; 
 
        $data = (object)$data;
        $oldid = $data->id;

        // TODO : Do not create a new lips instance if one already exists in the current course.

        $data->course = $this->get_courseid();

        $data->timecreated = time();
        $data->timemodified = time();
        
        // Insert the lips record in db.
        $newitemid = $DB->insert_record('lips', $data);
        $this->apply_activity_instance($newitemid);
        $this->lipsid = $newitemid;
    }

    /**
     * Restore category item if it doesn't already exist in the lips instance.
     */
    protected function process_lips_category($data) {
        global $DB, $PAGE;

        $data = (object)$data;
        $oldid = $data->id;

        $sql = "
            SELECT *
            FROM mdl_lips_category
            WHERE id_language = " . $this->lipsid . "
            AND category_name = '" . $data->category_name . "'";

        $categories = $DB->get_records_sql($sql);

        // The category already exists in the current instance.
        if ($categories) {
            foreach ($categories as $category) {
                $this->set_mapping('lips_category', $oldid, $category->id);
            }
        }
        else {
            // Set current language id.
            $data->id_language = $this->lipsid;

            // Insert the category record in db.
            $newitemid = $DB->insert_record('lips_category', $data);
            $this->set_mapping('lips_category', $oldid, $newitemid);
        }
    }

    /**
     * Restore problem item if it doesn't already exist in the lips instance
     * for the same category.
     */
    protected function process_lips_problem($data) {
        global $DB, $USER;
 
        $data = (object)$data;
        $oldid = $data->id;

        $sql_problems = "
            SELECT prob.id
            FROM mdl_lips_category cat, mdl_lips_problem prob
            WHERE cat.id = prob.problem_category_id
            AND cat.id_language = " . $this->lipsid . "
            AND prob.problem_label = '" . $data->problem_label . "'
            AND cat.id = " . $this->get_mappingid('lips_category', $data->problem_category_id);
        
        $problems = $DB->get_records_sql($sql_problems);

         // The problem doesn't exist in the lips instance for the same category.
        if (!$problems) {
            $data->problem_creator_id = $USER->id;
            $data->problem_category_id = $this->get_mappingid('lips_category', $data->problem_category_id);
            
            // Let the old difficulty id, waiting the difficulty restoration to update it.

            $data->problem_date = time();
            $data->problem_attempts = 0;
            $data->problem_testing = 0;

            $newitemid = $DB->insert_record('lips_problem', $data);
            $this->newproblemsidarray[] = $newitemid;
        }
    }

     /**
     * Restore difficulty item if it doesn't already exist.
     */
    protected function process_lips_difficulty($data) {
        global $DB;
 
        $data = (object)$data;
        $oldid = $data->id;
        
        $sql = "
            SELECT *
            FROM mdl_lips_difficulty
            WHERE difficulty_label = '" . $data->difficulty_label . "'";

        $difficulties = $DB->get_records_sql($sql);

        // The difficulty already exists in db.
        if ($difficulties) {
            foreach ($difficulties as $difficulty) {
                $this->set_mapping('lips_difficulty', $oldid, $difficulty->id);
            }
        }
        else {
            // Insert the difficulty record in db.
            $newitemid = $DB->insert_record('lips_difficulty', $data);
            $this->set_mapping('lips_difficulty', $oldid, $newitemid);
        }
    }

    protected function after_execute() {
        // Add lips related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_lips', 'intro', null);

        // Restore difficulty id of all problems that have been added.
        foreach ($newproblemsidarray as $newproblemsid) {

            // Get the old difficulty id of the problem in db.
            $problem = get_problem_details($newproblemsid);
            $oldid = $problem->difficulty_id;
            $currentid = $this->get_mappingid('lips_difficulty', $oldid);
            $DB->execute("
                UPDATE mdl_lips_problem
                SET problem_difficulty_id = " . $currentid . "
                WHERE problem_difficulty_id = " . $oldid);
        }
    }
}