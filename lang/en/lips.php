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
 * English strings for lips
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*--------------------------------------------------
 * Module informations
 *------------------------------------------------*/
$string['modulename'] = 'LIPS';
$string['modulenameplural'] = 'LIPS';
$string['modulename_help'] = 'Use the lips module for... | The lips module allows...';
$string['lipsfieldset'] = 'Custom example fieldset';
$string['lipsname'] = 'LIPS';
$string['lipsname_help'] = 'This is the content of the help tooltip associated with the lipsname field. Markdown syntax is supported.';
$string['lips'] = 'LIPS';
$string['pluginadministration'] = 'lips administration';
$string['pluginname'] = 'LIPS';

/*--------------------------------------------------
 * Constants
 *------------------------------------------------*/
$string['create'] = 'Create';
$string['modify'] = 'Modify';
$string['category'] = 'Catégorie';
$string['documentation'] = 'Documentation';
$string['name'] = 'Name';

/*--------------------------------------------------
 * Tabs
 *------------------------------------------------*/
$string['index'] = 'Index';
$string['problems'] = 'Problems';
$string['users'] = 'Users';
$string['rank'] = 'Rank';
$string['profile'] = 'Profile';
$string['administration'] = 'Administration';

/*--------------------------------------------------
 * Administration
 *------------------------------------------------*/

// Language
$string['administration_language_configure_title'] = 'Configure language';
$string['administration_language_configure_msg'] = 'Select the language in which you want to compile.';
$string['administration_language_image_title'] = 'Picture';
$string['administration_language_image_msg'] = 'Choose the picture for this programming language.<br/>This one will be used at the rank display.';
$string['administration_language_code_title'] = 'Base code';
$string['administration_language_code_msg'] = 'Enter the base used for each compilation.<br/>Before the compilation :<ul><li>The <span style="color: red;">&lt;lips-preconfig-import/&gt;</span> tag will be replaced by the "<strong>Import libaries</strong>" content;</li><li>The <span style="color: red;">&lt;lips-preconfig-code/&gt;</span> tag will be replace by the "<strong>Code complete</strong>" content;</li><li>The <span style="color: red;">&lt;lips-preconfig-tests/&gt;</span> tag will be replaced by the "<strong>Unit tests</strong>" content.</li></ul>';
$string['administration_language_form_select'] = 'Programming language';
$string['administration_language_form_select_error'] = 'You must select a language';
$string['administration_language_form_file'] = 'Select the picture';
$string['administration_language_form_file_error'] = 'You must select a picture';

// Category
$string['administration_category_create_title'] = 'Create a category';
$string['administration_category_name_error'] = 'You must enter a category name';
$string['administration_category_documentation_placeholder'] = 'Link to an external documentation';
$string['administration_delete_category_confirmation'] = "Confirm the deletion of the category";

//Web services
$string['web_service_communication_error'] = 'An error occured when contacting the web service';

