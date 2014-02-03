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
 * Does something really useful with the passed things
 *
 * @param array $things
 * @return object
 */
//function lips_do_something_useful(array $things) {
//    return new stdClass();
//}

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