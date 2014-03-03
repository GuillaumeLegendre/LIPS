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
 * @package   mod_lips
 * @copyright 2014 LIPS
 *
 * @author Valentin Got
 * @author Guillaume Legendre
 * @author Mickael Ohlen
 * @author Anaïs Picoreau
 * @author Julien Senac
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
$id = $_GET['id'];
$PAGE->set_url('/mod/lips/get_problems_by_category.php', array('id' => $id));
$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
if (isset($_GET['idproblem'])) {
    $idproblem = $_GET['idproblem'];
    $problems = $DB->get_records_sql('SELECT * from mdl_lips_problem WHERE problem_category_id='
        . $id . ' AND id <> ' . $idproblem);
} else {
    $problems = $DB->get_records('lips_problem', array('problem_category_id' => $id));
}
echo $OUTPUT->header();
echo json_encode($problems);
echo $OUTPUT->footer();