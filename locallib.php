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
        "deleteCategory" => "poblems"
    );
    return $tabs[$view];
}

/**
 * Fetch all categories of the current instance
 * 
 * @param int Id the the current instance
 * @return object List of all categories of the current instance
 */
function fetch_all_categories($id_language) {
    global $DB;

    return $DB->get_records('lips_category', array('id_language' => $id_language));
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
 * Delete a category of the database
 *
 * @param int $id Id the of the category to delete
 */
function delete_category($id) {
    global $DB;
    
    $DB->delete_records("lips_category", array("id" => $id));
}

/**
 * Test if the category already exists
 *
 * @param array $conditions Category fields
 * @return bool True if the category already exists, otherwise false
 */
function category_exists($conditions) {
    global $DB;

    if($DB->count_records('lips_category', $conditions) > 0)
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