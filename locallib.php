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
 * Return the user highest role
 *
 * @return string Highest role of the user
 */
function get_highest_role() {
    global $USER;

    $cm = get_coursemodule_from_id('lips', optional_param('id', 0, PARAM_INT), 0, false, MUST_EXIST);
    $context = context_module::instance($cm->id);

    $roles = get_user_roles($context, $USER->id);

    foreach ($roles as $role) {
        if (in_array('manager', (array)$role) || in_array('coursecreator', (array)$role)) {
            return 'coursecreator';
        }
    }

    foreach ($roles as $role) {
        if (in_array('editingteacher', (array)$role) || in_array('teacher', (array)$role)) {
            return 'teacher';
        }
    }

    foreach ($roles as $role) {
        if (in_array('student', (array)$role)) {
            return 'student';
        }
    }

    return null;
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
        "profile" => "profile",
        "users" => "users",
        "category" => "problems",
        "categoryDocumentation" => "problems",
        "deleteCategory" => "problems",
        "problem" => "problems",
        "deleteProblem" => "problems",
        "solutions" => "problems"
    );

    return $tabs[$view];
}

/**
 * Get details of a specific user.
 *
 * @param array $conditions Conditions to match the user.
 * @return object An array containing the details of the requested user.
 */
function get_user_details(array $conditions = null) {
    global $DB;

    return $DB->get_record('lips_user', $conditions, '*');
}

/**
 * Get details of a specific moodle user.
 *
 * @param array $conditions Conditions to match the user.
 * @return object An array containing the details of the requested user.
 */
function get_moodle_user_details(array $conditions = null) {
    global $DB;

    return $DB->get_record('user', $conditions, '*');
}

/**
 * Insert the user in the database if not already present
 */
function insert_user_if_not_exists() {
    global $USER;

    $user = get_user_details(array('id_user_moodle' => $USER->id));
    if ($user == null) {
        $role = get_highest_role();
        if ($role != null) {
            insert_user($USER->id, get_highest_role(), 1, 0);
        }
    }
}

/**
 * Insert a new user
 *
 * @param int $idusermoodle ID of the user on moodle
 * @param string $userstatus User status
 * @param int $userrankid User rank id
 * @param int $userscore
 */
function insert_user($idusermoodle, $userstatus, $userrankid, $userscore) {
    global $DB;

    $DB->insert_record('lips_user', array(
        'id_user_moodle' => $idusermoodle,
        'user_status' => $userstatus,
        'user_rank_id' => $userrankid,
        'user_score' => $userscore
    ));
}

/**
 * Get the user picture url
 *
 * @param array $conditions Conditions to match the user
 * @param string $size Base picture size (f1, f2 or f3)
 * @return string URL of the user picture
 */
function get_user_picture_url($conditions, $size = 'f2') {
    global $PAGE;

    $user = get_moodle_user_details(array('id' => get_user_details($conditions)->id_user_moodle));

    $userpicture = new user_picture($user);
    return str_replace('f2', $size, $userpicture->get_url($PAGE));
}

/**
 * Get details of a specific rank.
 *
 * @param array $conditions Conditions to match the rank.
 * @return object An array containing the details of the requested rank.
 */
function get_rank_details(array $conditions = null) {
    global $DB;

    return $DB->get_record('lips_rank', $conditions, '*');
}

/**
 * Fetch all removable categories of the current instance (no problem is linked to the category)
 *
 * @param int $idlanguage Id of the current instance
 * @return object List of all removable categories of the current instance
 */
function fetch_removable_categories($idlanguage) {
    global $DB;

    return $DB->get_records_sql('
        SELECT mlc.id, category_name
        FROM mdl_lips_category mlc
        LEFT JOIN mdl_lips_problem mlp ON mlc.id = mlp.problem_category_id
        WHERE mlc.id_language = ' . $idlanguage . '
        GROUP BY mlc.id HAVING COUNT(mlp.id) = 0');
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
 * Update the language picture
 *
 * @param string $picture New picture
 */
function update_language_picture($picture) {
    global $DB;

    // Current instance
    $lips = get_current_instance();

    $DB->update_record('lips', array('id' => $lips->id, 'language_picture' => $picture));
}

/**
 * Delete a picture
 *
 * @param string $picture Picture to delete
 */
function delete_picture_file($picture) {
    unlink('./images/' . $picture);
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
 * Count the number of problems present on the current instance
 *
 * @param int Id of the current instance
 * @return int Number of problems present on the current instance
 */
function count_languages_number($idlanguage) {
    global $DB;

    return $DB->count_records_sql('
        SELECT count(*)
        FROM mdl_lips_category mlc, mdl_lips_problem mlp
        WHERE mlc.id = mlp.problem_category_id
        AND id_language = ' . $idlanguage);
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
 * @param int $id Problem ID
 * @return object An array containing the details of a category.
 */
function get_problem_details($id) {
    global $DB;

    return $DB->get_records_sql("select mlp.id,problem_label,problem_date,problem_creator_id,problem_attempts, difficulty_label, problem_preconditions, problem_statement, problem_tips, problem_unit_tests,problem_category_id, count(mls.id) as problem_resolutions, firstname, lastname, mlu.id AS user_id from mdl_lips_problem mlp join mdl_lips_difficulty mld on problem_difficulty_id=mld.id left join mdl_lips_problem_solved mls ON mls.problem_solved_problem = mlp.id join mdl_user mu on mu.id=problem_creator_id JOIN mdl_lips_user mlu ON mlu.id_user_moodle = problem_creator_id where mlp.id=" . $id);
}

/**
 * Get the similar problems of a problem
 *
 * @param int $mainproblemid Main problem ID
 * @return object The similar problems of a problem
 */
function get_similar_problems($mainproblemid) {
    global $DB;

    return $DB->get_records('lips_problem_similar', array('problem_similar_main_id' => $mainproblemid));
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
 * Remove from the Database the problem with the id in parameter.
 *
 * @param int $id Id the of the category to delete
 */
function delete_problem($id) {
    global $DB;

    $DB->delete_records("lips_problem", array("id" => $id));
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

/**
 * Test if the category already exists
 *
 * @param array $conditions Category fields
 * @return bool True if the category already exists, otherwise false
 */
function category_exists($conditions) {
    global $DB;

    if ($DB->count_records('lips_category', $conditions) > 0) {
        return true;
    }
    return false;
}

/**
 * Test if a category is removable, ie category is empty
 *
 * @param int $id Category id
 * @return bool True if the category is removable, otherwise false
 */
function is_removable($id, $idlanguage) {
    global $DB;

    $sql = "SELECT mlc.id, category_name
        FROM mdl_lips_category mlc
        LEFT JOIN mdl_lips_problem mlp
        ON mlc.id = mlp.problem_category_id
        WHERE mlc.id = " . $id . "
        AND mlc.id_language = " . $idlanguage . "
        GROUP BY mlc.id HAVING COUNT(mlp.id) = 0";

    return $DB->record_exists_sql($sql);
}

/**
 * Insert a category to the database
 *
 * @param int $idlanguage Language id
 * @param string $categoryname Category name
 * @param string $categorydocumentation Category documentation
 * @param string $categorydocumentationtype Category documentation type (LINK or TEXT)
 */
function insert_category($idlanguage, $categoryname, $categorydocumentation, $categorydocumentationtype) {
    global $DB;

    $DB->insert_record('lips_category', array(
        'id_language' => $idlanguage,
        'category_name' => $categoryname,
        'category_documentation' => $categorydocumentation,
        'category_documentation_type' => $categorydocumentationtype));
}

/**
 * Update a category
 *
 * @param int $id Category id
 * @param string $categoryname Category name
 * @param string $categorydocumentation Category documentation
 * @param string $categorydocumentationtype Category documentation type (LINK or TEXT)
 */
function update_category($id, $categoryname, $categorydocumentation, $categorydocumentationtype) {
    global $DB;

    $DB->update_record('lips_category', array('id' => $id, 'category_name' => $categoryname, 'category_documentation' => $categorydocumentation, 'category_documentation_type' => $categorydocumentationtype));
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
 * @return object The number of resolutions of a user
 */
function nb_resolutions_problem($iduser, $idproblem) {
    global $DB;

    return $DB->count_records("lips_problem_solved", array('problem_solved_problem' => $idproblem, 'problem_solved_user' => $iduser));
}


/**
 * Get the displayable unit tests
 *
 * @param string $unittests Problem unit tests
 * @return array The displayable unit tests
 */
function get_displayable_unittests($unittests) {
    preg_match_all("|<lips-unit-test>(.*?)</lips-unit-test>|U", $unittests, $out);

    return $out;
}

/**
 * Get the test picture
 *
 * @return string The test picture
 */
function get_unitest_picture() {
    return "test.png";
}

/**
 * Fetch all problems of a user
 *
 * @param int Id of the user
 * @return object List of all problems of the user
 * */
function fetch_problems($userid) {
    global $DB;
    return $DB->get_records('lips_problem', array('problem_creator_id' => $userid));
}


/**
 * Return all solutions of a specific problem
 *
 * @param int Id of the problem
 * @return object List of all solutions of the problem
 * */
function get_solutions($problemid, $search = null) {
    global $DB;
    if ($search == null) {
        return $DB->get_records_sql("select mls.id, mlu.id as profil_id, firstname, lastname, problem_solved_date, problem_solved_solution
        from mdl_lips_problem_solved mls
        join mdl_user mu on mu.id=mls.problem_solved_user
        join mdl_lips_user mlu on mlu.id_user_moodle=mls.problem_solved_user
        where problem_solved_problem = $problemid");
    } else {
        return $DB->get_records_sql("select mls.id, mlu.id as profil_id, firstname, lastname, problem_solved_date, problem_solved_solution
        from mdl_lips_problem_solved mls
        join mdl_user mu on mu.id=mls.problem_solved_user
        join mdl_lips_user mlu on mlu.id_user_moodle=mls.problem_solved_user
        where problem_solved_problem = $problemid
        and (mu.firstname like '%" . $search . "%' or mu.lastname like '%" . $search . "%')");
    }
}

/**
 * Test if $followed if following $followed
 *
 * @param int $follower User who follow the other user
 * @param int $followed User followed by the other user
 * @return bool True if $follower is following $followed, otherwise false
 */
function is_following($follower, $followed) {
    global $DB;

    $result = $DB->count_records('lips_follow', array('follower' => $follower, 'followed' => $followed), '*');
    return $result >= 1;
}

/**
 * Follow a user
 *
 * @param int $follower Follower
 * @param int $followed Followed user
 */
function follow($follower, $followed) {
    global $DB;

    $DB->insert_record('lips_follow', array('follower' => $follower, 'followed' => $followed));
}

/**
 * Unfollow a user
 *
 * @param int $follower Follower
 * @param int $followed Followed user
 */
function unfollow($follower, $followed) {
    global $DB;

    $DB->delete_records('lips_follow', array('follower' => $follower, 'followed' => $followed));
}