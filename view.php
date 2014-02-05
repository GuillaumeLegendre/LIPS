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
 * Prints a particular instance of lips
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_lips
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// (Replace lips with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/pagelib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n = optional_param('n', 0, PARAM_INT); // lips instance ID - it should be named as the first character of the module
$view = optional_param('view', 0, PARAM_TEXT); // lips instance ID - it should be named as the first character of the module
if (!$view) {
    $view = "index";
}


if ($id) {
    $cm = get_coursemodule_from_id('lips', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $lips = $DB->get_record('lips', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $lips = $DB->get_record('lips', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $lips->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('lips', $lips->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = context_module::instance($cm->id);

add_to_log($course->id, 'lips', 'view', "view.php?id={$cm->id}", $lips->name, $cm->id);

$PAGE->set_url('/mod/lips/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($lips->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$viewpage = new page_index($cm);

switch ($view) {
    case "administration" :
        if (has_capability('mod/lips:administration', $context)) {
            $action = optional_param('action', null, PARAM_TEXT);

            switch ($action) {
                case "langage":
                    $viewpage = new page_admin_langage($cm);
                    break;

                case "category_create":
                    $viewpage = new page_admin_category_create($cm);
                    break;
                
                default:
                    $viewpage = new page_admin($cm);
                    break;
            }
        }
        break;
    case "problems" :
        $viewpage = new page_list_categories($cm);
        break;
    case "profil" :
        $viewpage = new page_profil($cm, $id);
        break;

    case "users" :
        $viewpage = new page_users($cm);
        break;
    case "category" :
        $idcategory = optional_param('categoryId', 0, PARAM_INT);
        $viewpage = new page_category($cm, $idcategory);
        break;
    default :
        $viewpage = new page_index($cm);
}

$viewpage->display();