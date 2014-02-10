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
 * Internal library of functions for module lips
 *
 * All the lips specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_lips
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Get the current instance of the plugin
 *
 * @return object The current instance of the plugin
 */
function get_current_instance() {
    global $DB;

    $cm = get_coursemodule_from_id('lips', optional_param('id', 0, PARAM_INT), 0, false, MUST_EXIST);

    return $DB->get_record('lips', array('id' => $cm->instance), '*', MUST_EXIST);
}

/**
 * Test if the current user has the requested role
 *
 * @param string $role Role to test
 * @return bool Return true if the current user has the requested role, otherwise return false
 */
function has_role($role) {
    $cm = get_coursemodule_from_id('lips', optional_param('id', 0, PARAM_INT), 0, false, MUST_EXIST);
    $context = context_module::instance($cm->id);

    return has_capability('mod/lips:' . $role, $context);
}

/**
 * Get the tab name corresponding to the view name in parameter.
 *
 * @return array The tab name corresponding to the view name in parameter.
 */
function convert_active_tab($view) {
    $tabs = array(
        "index" => "index",
        "administration" => "administration",
        "problems" => "problems",
        "profil" => "profil",
        "users" => "users",
        "category" => "problems",
        "categoryDocumentation" => "problems",
        "deleteCategory" => "problems",
        "problem" => "problems",
    );
    return $tabs[$view];
}

/**
 * Fetch all categories of the current instance
 *
 * @param int Id of the current instance
 * @return object List of all categories of the current instance
 */
function fetch_all_categories($idlanguage) {
    global $DB;

    return $DB->get_records('lips_category', array('id_language' => $idlanguage));
}

/**
 * Count the number of languages present on the current instance
 *
 * @param int Id of the current instance
 * @return object List of all languages of the current instance
 */
function count_languages_number($idlanguage) {
    global $DB;

    return $DB->count_records_sql('SELECT count(*) FROM mdl_lips_category mlc, mdl_lips_problem mlp WHERE mlc.id = mlp.problem_category_id AND id_language = ' . $idlanguage);
}


/**
 * Fetch all difficulties.
 *
 * @return object List of all difficulties
 */
function fetch_all_difficulties() {
    global $DB;

    return $DB->get_records('lips_difficulty');
}

/**
 * Get details of a specific category.
 *
 * @return object An array containing the details of a category.
 */
function get_category_details($id) {
    global $DB;

    return $DB->get_record('lips_category', array('id' => $id), '*', MUST_EXIST);
}

/**
 * Get details of a specific category.
 *
 * @return object An array containing the details of a category.
 */
function get_problem_details($id) {
    global $DB;
    return $DB->get_records_sql("select mlp.id,problem_label,problem_date,problem_creator_id,problem_attempts, difficulty_label, problem_preconditions, problem_statement, problem_tips, problem_unit_tests from mdl_lips_problem mlp join mdl_lips_difficulty mld on problem_difficulty_id=mld.id where mlp.id=" . $id);
}

/**
 * Get the language picture
 *
 * @return string The language picture
 */
function get_language_picture() {
    global $DB;

    $id = optional_param('id', 0, PARAM_INT);
    $cm = get_coursemodule_from_id('lips', $id, 0, false, MUST_EXIST);

    return $DB->get_record('lips', array('id' => $cm->instance), 'language_picture', MUST_EXIST)->language_picture;
}

/**
 * Remove from the Database the category with the id in parameter.
 *
 * @param int $id Id the of the category to delete
 */
function delete_category($id) {
    global $DB;

    $DB->delete_records("lips_category", array("id" => $id));
}

/**
 * Return true if the user is the author of the problem in parameter.
 *
 * @return boolean true if the user is the author of the problem.
 */
function is_author($idproblem, $iduser) {
    global $DB;
    return $DB->get_record("lips_problem", array('id' => $idproblem))->problem_creator_id == $iduser;
}

/* Test if the category already exists
 *
 * @param array $conditions Category fields
 * @return bool True if the category already exists, otherwise false
 */
function category_exists($conditions) {
    global $DB;

    if ($DB->count_records('lips_category', $conditions) > 0)
        return true;
    return false;
}

/**
 * Insert a category to the database
 *
 * @param int $id_language Language id
 * @param string $category_name Category name
 * @param string $category_documentation Category documentation
 * @param string $category_documentation_type Category documentation type (LINK or TEXT)
 */
function insert_category($id_language, $category_name, $category_documentation, $category_documentation_type) {
    global $DB;

    $DB->insert_record('lips_category', array('id_language' => $id_language, 'category_name' => $category_name, 'category_documentation' => $category_documentation, 'category_documentation_type' => $category_documentation_type));
}

/**
 * Update a category
 *
 * @param int $id Category id
 * @param string $category_name Category name
 * @param string $category_documentation Category documentation
 * @param string $category_documentation_type Category documentation type (LINK or TEXT)
 */
function update_category($id, $category_name, $category_documentation, $category_documentation_type) {
    global $DB;

    $DB->update_record('lips_category', array('id' => $id, 'category_name' => $category_name, 'category_documentation' => $category_documentation, 'category_documentation_type' => $category_documentation_type));
}

function has_documentation($idcategory) {
    $cat = get_category_details($idcategory);

    if ($cat->category_documentation) {
        return true;
    }

    return false;
}

/**
 * Update a language
 *
 * @param int $id Language id
 * @param array $data Data to update
 */
function update_language($id, $data) {
    global $DB;

    $DB->update_record('lips', array_merge(array('id' => $id), (array)$data));
}

/**
 * Get the available languages for the ace plugin
 *
 * @return array An array of available languages
 */
function ace_available_languages() {
    $dir = './ace/ace-builds/src-noconflict';
    $files = scandir($dir, 1);
    $languages = array();

    foreach ($files as $value) {
        if (preg_match('/^mode-.*/', $value) === 1) {
            $language = preg_replace(array('/mode-/', '/\.js/'), array('', ''), $value);
            $languages[$language] = $language;
        }
    }

    asort($languages);

    return $languages;
}

/**
 * Returns the number of resolutions of a user.
 *
 * @param int $idproblem Problem id
 * @param int $iduser User id
 * @param int number of resolutions
 */
function nb_resolutions_problem($iduser, $idproblem) {
    global $DB;
    return $DB->count_records("lips_problem_solved", array('problem_solved_problem' => $idproblem, 'problem_solved_user' => $iduser));
}
