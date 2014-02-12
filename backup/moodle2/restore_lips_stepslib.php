<?php

/**
 * Structure step to restore one lips activity
 */
class restore_lips_activity_structure_step extends restore_activity_structure_step {

    private $mod_id;
 
    protected function define_structure() {
 
        $paths = array();
 
        $paths[] = new restore_path_element('lips', '/activity/lips');
        $paths[] = new restore_path_element('lips_difficulty', '/activity/lips/difficulties/difficulty');
        $paths[] = new restore_path_element('lips_category', '/activity/lips/categories/category');
        $paths[] = new restore_path_element('lips_problem', '/activity/lips/problems/problem');
 
        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }
 
    protected function process_lips($data) {
        global $DB;
        global $PAGE; 
 
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

            $data->timecreated = $this->apply_date_offset($data->timecreated);
            $data->timemodified = $this->apply_date_offset($data->timemodified);
            
            // Insert the lips record.
            // $newitemid = $DB->insert_record('lips', $data);
            // $this->apply_activity_instance($newitemid);
            // $this->mod_id = $newitemid;
        }
    }
 
    protected function process_lips_difficulty($data) {
        global $DB;
 
        $data = (object)$data;
        $oldid = $data->id;
        
        $sql = "
            SELECT *
            FROM mdl_lips_difficulty
            WHERE difficulty_label = '" . $data->difficulty_label . "'";

        $difficulties = $DB->get_records_sql($sql);
        // The difficulty already exists in db
        if ($difficulties) {
            foreach ($difficulties as $difficulty) {
                 $this->set_mapping('lips_difficulty', $oldid, $difficulty->id);
            }
        }
        else {
            $newitemid = $DB->insert_record('lips_difficulty', $data);
            $this->set_mapping('lips_difficulty', $oldid, $newitemid);
        }
    }
 
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

        // The category already exists in db
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
            $newitemid = $DB->insert_record('lips_category', $data);
            $this->set_mapping('lips_category', $oldid, $newitemid);
        }
    }

    protected function process_lips_problem($data) {
        global $DB;
        global $USER;
 
        $data = (object)$data;
        $userinfo = $this->get_setting_value('userinfo');
        
        $data->problem_category_id = $this->get_mappingid('lips_category', $data->problem_category_id);
        $data->problem_difficulty_id = $this->get_mappingid('lips_difficulty', $data->problem_difficulty_id);
        $data->problem_date = $this->apply_date_offset($data->problem_date);
        $data->problem_attempts = 0;

        // We are not including user info, so don't restore the old creator, use the current user.
        if (!$userinfo) {
            $data->problem_creator_id = $USER->id;
            $data->problem_date = time();
        }
 
        $newitemid = $DB->insert_record('lips_problem', $data);
    }
 
    protected function after_execute() {
        // Add lips related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_lips', 'intro', null);
    }
}