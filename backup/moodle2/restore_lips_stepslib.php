<?php

/**
 * Structure step to restore one lips activity
 */
class restore_lips_activity_structure_step extends restore_activity_structure_step {

    private $mod_id;
 
    protected function define_structure() {
 
        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');
 
        $paths[] = new restore_path_element('lips', '/activity/lips');
        $paths[] = new restore_path_element('lips_difficulty', '/activity/lips/difficulties/difficulty');
        $paths[] = new restore_path_element('lips_category', '/activity/lips/categories/category');
        $paths[] = new restore_path_element('lips_problem', '/activity/lips/problems/problem');
 
        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }
 
    protected function process_lips($data) {
        global $DB;
 
        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
 
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the lips record
        $newitemid = $DB->insert_record('lips', $data);
        print_object($newitemid);
        $this->mod_id = $newitemid->id;
        $this->apply_activity_instance($newitemid);
    }
 
    protected function process_lips_difficulty($data) {
        global $DB;
 
        $data = (object)$data;
        $oldid = $data->id;
 
        $newitemid = $DB->insert_record('lips_difficulty', $data);
        $this->set_mapping('lips_difficulty', $oldid, $newitemid);
    }
 
    protected function process_lips_category($data) {
        global $DB;
 
        $data = (object)$data;
        $oldid = $data->id;

        $data->id_language = $this->mod_id;
 
        $newitemid = $DB->insert_record('lips_category', $data);
        $this->set_mapping('lips_category', $oldid, $newitemid);
    }

    protected function process_lips_problem($data) {
        global $DB;
        global $USER;
 
        $data = (object)$data;

        // if pas user_info, problem_creator_id = user courant et problem_date = today
        $data->problem_creator_id = $USER->id;

        $data->problem_category_id = $this->get_mappingid('lips_category', $data->problem_category_id);
        $data->problem_difficulty_id = $this->get_mappingid('lips_difficulty', $data->problem_difficulty_id);
        $data->problem_date = $this->apply_date_offset($data->problem_date);
        $date->problem_attempts = 0;
 
        $newitemid = $DB->insert_record('lips_problem', $data);
    }
 
    protected function after_execute() {
        // Add lips related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_lips', 'intro', null);
    }
}