<?php
 
/**
 * Define all the backup steps that will be used by the backup_lips_activity_task
 */

/**
 * Define the complete lips structure for backup, with file and id annotations
 */     
class backup_lips_activity_structure_step extends backup_activity_structure_step {
 
    protected function define_structure() {
 
        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');
 
        // Define each element separated

        $lips = new backup_nested_element('lips', array('id'), array(
        	'name', 'intro', 'introformat', 'compile_language', 'coloration_language',
        	'language_picture', 'base_code'));

        $difficulties = new backup_nested_element('difficulties');

        $difficulty = new backup_nested_element('difficulty', array('id'), array(
        	'difficulty_label', 'difficulty_points'));
 
        $categories = new backup_nested_element('categories');
 
        $category = new backup_nested_element('category', array('id'), array(
            'category_name', 'category_documentation', 'category_documentation_type'));
 
        $problems = new backup_nested_element('problems');
 
        $problem = new backup_nested_element('problem', array('id'), array(
            'problem_creator_id', 'problem_category_id', 'problem_label', 'problem_difficulty_id',
            'problem_preconditions', 'problem_statement', 'problem_tips', 'problem_code', 'problem_unit_tests'));
 
        // Build the tree
 		$lips->add_child($difficulties);
        $difficulties->add_child($difficulty);
 
        $lips->add_child($categories);
        $categories->add_child($category);

        $lips->add_child($problems);
        $problems->add_child($problem);

        // Define sources
        $lips->set_source_table('mdl_lips', array('id' => backup::VAR_ACTIVITYID));

 		$difficulty->set_source_table('mdl_lips_difficulty', array('id' => backup::VAR_ACTIVITYID));

 		$category->set_source_table('mdl_lips_category', array('id' => backup::VAR_ACTIVITYID));

 		$problem->set_source_table('mdl_lips_problem', array('id' => backup::VAR_ACTIVITYID));
 
        // All the rest of elements only happen if we are including user info
       /* if ($userinfo) {
            $answer->set_source_table('choice_answers', array('choiceid' => '../../id'));
        }*/

        // Define id annotations
 
        // Define file annotations
        $lips->annotate_files('mdl_lips', 'intro', null); // This file area hasn't itemid

        // Return the root element (lips), wrapped into standard activity structure
        return $this->prepare_activity_structure($lips);
    }
}