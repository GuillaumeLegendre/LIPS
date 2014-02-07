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
 * This file replaces the legacy STATEMENTS section in db/install.xml,
 * lib.php/modulename_install() post installation hook and partially defaults.php
 *
 * @package    mod_lips
 * @copyright  2011 Your Name <your@email.adress>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Post installation procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_lips_install() {
    global $DB;
    $DB->insert_record("lips_difficulty", array("difficulty_label" => "difficulty_label_elementary", "difficulty_points" => "1"));
    $DB->insert_record("lips_difficulty", array("difficulty_label" => "difficulty_label_easy", "difficulty_points" => "5"));
    $DB->insert_record("lips_difficulty", array("difficulty_label" => "difficulty_label_medium", "difficulty_points" => "10"));
    $DB->insert_record("lips_difficulty", array("difficulty_label" => "difficulty_label_difficult", "difficulty_points" => "15"));
}

/**
 * Post installation recovery procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_lips_install_recovery() {
}
