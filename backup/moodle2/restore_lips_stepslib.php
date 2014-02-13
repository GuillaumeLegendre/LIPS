<?php

/**
 * Structure step to restore one lips activity
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_lips_activity_structure_step extends restore_activity_structure_step {

    private $mod_id;
 
    /**
     * Define the structure of the tree to restore.
     */
    protected function define_structure() {
 
        $paths = array();
 
        $paths[] = new restore_path_element('lips', '/activity/lips');
        $paths[] = new restore_path_element('lips_difficulty', '/activity/lips/difficulties/difficulty');
        $paths[] = new restore_path_element('lips_category', '/activity/lips/categories/category');
        $paths[] = new restore_path_element('lips_problem', '/activity/lips/problems/problem');
 
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

        $cm = get_coursemodule_from_id('lips', optional_param('id', 0, PARAM_INT), 0, false, MUST_EXIST);
        $this->mod_id = $cm->id;

        $sql = "
            SELECT count(*)
            FROM mdl_course_modules cm
            JOIN mdl_modules md ON md.id = cm.module
            WHERE cm.id = " . $this->mod_id . " AND md.name = 'lips'";

         // We create a new lips instance : the current course has no instance for lips.
        if (!$DB->count_records_sql($sql)) {

            echo "create a new instance of lips";

            $data->course = $this->get_courseid();

            $data->timecreated = time();
            $data->timemodified = time();
            
            // Insert the lips record in db.
            $newitemid = $DB->insert_record('lips', $data);
            $this->apply_activity_instance($newitemid);
            $this->mod_id = $newitemid;
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

    /**
     * Restore category item if it doesn't already exist in the lips instance.
     */
    protected function process_lips_category($data) {
        global $DB, $PAGE;

        $data = (object)$data;
        $oldid = $data->id;

        $sql = "
            SELECT *
            FROM mdl_course_modules cm, mdl_lips lips, mdl_lips_category cat
            WHERE cat.id_language = lips.id
            AND lips.id = cm.instance
            AND cm.id = " . $this->mod_id . "
            AND category_name = '" . $data->category_name . "'";

        $categories = $DB->get_records_sql($sql);

        // The category already exists in db.
        if ($categories) {
            foreach ($categories as $category) {
                $this->set_mapping('lips_category', $oldid, $category->id);
            }
        }
        else {
            $sql_id_language = "
                SELECT lips.id
                FROM mdl_course_modules cm, mdl_lips lips
                WHERE lips.id = cm.instance
                AND cm.id = " . $this->mod_id;

            $id_language = $DB->get_record_sql($sql_id_language);

            // Set current language id.
            $data->id_language = $id_language->id;

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

        $sql_problems = "
            SELECT *
            FROM mdl_course_modules cm, mdl_lips lips, mdl_lips_category cat, mdl_lips_problem prob
            WHERE cat.id_language = lips.id
            AND lips.id = cm.instance
            AND cm.id = " . $this->mod_id . "
            AND prob.problem_category_id = cat.id
            AND prob.problem_label = '" . $data->problem_label . "'";
        
        $problems = $DB->get_records_sql($sql_problems);

         // The problem already exists in db.
        if ($problems) {
            foreach ($problems as $problem) {
                $this->set_mapping('lips_problem', $oldid, $problem->id);
            }
        }
        else {
            $data->problem_creator_id = $USER->id;
            $data->problem_category_id = $this->get_mappingid('lips_category', $data->problem_category_id);
            $data->problem_difficulty_id = $this->get_mappingid('lips_difficulty', $data->problem_difficulty_id);
            $data->problem_date = time();
            $data->problem_attempts = 0;
            // $data->problem_testing = 0;

            $newitemid = $DB->insert_record('lips_problem', $data);
        }
    }
 
    protected function after_execute() {
        // Add lips related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_lips', 'intro', null);
    }
}