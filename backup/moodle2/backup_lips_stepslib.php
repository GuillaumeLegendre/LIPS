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
 * Define all the backup steps that will be used by the backup_lips_activity_task
 *
 * @package     mod_lips
 * @subpackage  backup-moodle2
 * @category    backup
 * @copyright   2014 LIPS
 *
 * @author Valentin Got
 * @author Guillaume Legendre
 * @author Mickael Ohlen
 * @author Anaïs Picoreau
 * @author Julien Senac
 *
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_lips_activity_structure_step extends backup_activity_structure_step {

    /**
     * Define the complete lips structure for backup, with file and id annotations
     */
    protected function define_structure() {

        // Define each element separated.

        $lips = new backup_nested_element('lips', array('id'), array(
            'name', 'intro', 'introformat', 'timecreated', 'timemodified', 'compile_language',
            'coloration_language', 'language_picture', 'base_code'));

        $difficulties = new backup_nested_element('difficulties');

        $difficulty = new backup_nested_element('difficulty', array('id'), array(
            'difficulty_label', 'difficulty_points'));

        $categories = new backup_nested_element('categories');

        $category = new backup_nested_element('category', array('id'), array(
            'category_name', 'category_documentation', 'category_documentation_type'));

        $achievements = new backup_nested_element('achievements');

        $achievement = new backup_nested_element('achievement', array('id'), array(
            'achievement_label', 'achievement_desc', 'achievement_picture', 'achievement_category', 'achievement_problems'));

        $problems = new backup_nested_element('problems');

        $problem = new backup_nested_element('problem', array('id'), array(
            'problem_creator_id', 'problem_category_id', 'problem_label', 'problem_difficulty_id',
            'problem_preconditions', 'problem_statement', 'problem_tips', 'problem_code', 'problem_unit_tests', 'problem_date'));

        $problemssimilars = new backup_nested_element('problems_similars');

        $problemsimilar = new backup_nested_element('problem_similar', array('id'), array(
            'problem_similar_main_id', 'problem_similar_id'));

        // Build the tree.
        $lips->add_child($categories);
        $categories->add_child($category);

        $category->add_child($achievements);
        $achievements->add_child($achievement);

        $category->add_child($problems);
        $problems->add_child($problem);

        $problem->add_child($difficulties);
        $difficulties->add_child($difficulty);

        $problem->add_child($problemssimilars);
        $problemssimilars->add_child($problemsimilar);

        // Define sources.
        $lips->set_source_table('lips', array('id' => backup::VAR_ACTIVITYID));

        $category->set_source_table('lips_category', array('id_language' => backup::VAR_PARENTID));

        $achievement->set_source_table('lips_achievement', array('achievement_category' => backup::VAR_PARENTID));

        // Only backup the displayable problems (problem_testing = 0).
        $problem->set_source_sql("
            SELECT *
            FROM {lips_problem}
            WHERE problem_testing = 0
            AND problem_category_id = ?",
            array(backup::VAR_PARENTID));

        $difficulty->set_source_table('lips_difficulty', array('id' => '../../problem_difficulty_id'));

        $problemsimilar->set_source_table('lips_problem_similar', array('problem_similar_main_id' => backup::VAR_PARENTID));

        // Define id annotations.

        // Define file annotations.
        $lips->annotate_files('lips', 'intro', null); // This file area hasn't itemid.

        // Return the root element (lips), wrapped into standard activity structure.
        return $this->prepare_activity_structure($lips);
    }
}