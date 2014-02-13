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
 * Number of elements per tabs 
 *------------------------------------------------*/
$string['users_table'] = 10;
$string['my_problems_table'] = 10;

/*--------------------------------------------------
 * Status
 *------------------------------------------------*/
$string['coursecreator'] = 'Course creator';
$string['teacher'] = 'Teacher';
$string['student'] = 'Student';

/*--------------------------------------------------
 * Difficulties
 *------------------------------------------------*/
$string['difficulty_label_elementary'] = 'elementary';
$string['difficulty_label_easy'] = 'easy';
$string['difficulty_label_medium'] = 'medium';
$string['difficulty_label_difficult'] = 'difficult';

/*--------------------------------------------------
 * Pictures
 *------------------------------------------------*/
$string['picture_default_language'] = 'default_language.png';
$string['picture_follow'] = 'follow.png';
$string['picture_test'] = 'test.png';
$string['picture_testing'] = 'testing.png';

/*--------------------------------------------------
 * Notifications
 *------------------------------------------------*/
$string['notification_follow'] = '{time} - {notification_from} is following {notification_to}';

/*--------------------------------------------------
 * Constants
 *------------------------------------------------*/
$string['create'] = 'Create';
$string['modify'] = 'Modify';
$string['delete'] = 'Delete';
$string['import'] = 'Import';
$string['export'] = 'Export';
$string['category'] = 'Category';
$string['documentation'] = 'Documentation';
$string['documentation'] = 'Documentation';
$string['name'] = 'Name';
$string['solutions'] = 'Solutions';
$string['subject'] = 'Subject';
$string['tips'] = 'Tips';
$string['none'] = 'None';
$string['search'] = 'Search';
$string['answer'] = 'Answer';
$string['edit'] = 'Edit';
$string['error_impossible'] = 'You shouldn\'t have access this page';
$string['language'] = 'Language';
$string['follow'] = 'Follow';
$string['unfollow'] = 'Unfollow';
$string['user'] = 'User';
$string['grade'] = 'Grade';
$string['ranks'] = 'Ranks';
$string['status'] = 'Status';
$string['solved_problems'] = 'Solved problems';
$string['challenges'] = 'Challenges';
$string['followed_users'] = 'Followed users';
$string['attempts'] = "attempts";
$string['The'] = "The";
$string['from'] = "from";
$string['prerequisite'] = "Prerequisite";
$string['difficulty'] = "Difficulty";
$string['send_response'] = "Send reponses";
$string['similar_problems'] = 'Similar problems';
$string['test_problem'] = 'Test the problem';
$string['untesting'] = 'Submit';
$string['testing'] = 'Test';

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
 * Tables
 *------------------------------------------------*/
$string['number_of_problems'] = 'Number of problems';
$string['problem'] = 'Problem';
$string['level'] = 'Level';
$string['date'] = 'Date';
$string['author'] = 'Author';
$string['number_of_resolutions'] = 'Number of resolutions';
$string['resolved'] = 'Resolved';
$string['configure'] = 'Configure';
$string['picture'] = 'Picture';
$string['base'] = 'Base';

/*--------------------------------------------------
 * Administration
 *------------------------------------------------*/

// Language
$string['administration_language_configure_title'] = 'Configure language';
$string['administration_language_configure_msg'] = 'Select the language in which you want to compile.';
$string['administration_language_configure_success'] = 'The language has been configured';
$string['administration_language_image_title'] = 'Picture';
$string['administration_language_image_msg'] = 'Choose the picture for this programming language.<br/>This one will be used at the rank display.';
$string['administration_language_image_success'] = 'The picture has been modified';
$string['administration_language_image_save_error'] = 'An error occured when saving the file. Please try again.';
$string['administration_language_code_title'] = 'Base code';
$string['administration_language_code_msg'] = 'Enter the base used for each compilation.<br/>Before the compilation :<ul><li>The <span style="color: red;">&lt;lips-preconfig-import/&gt;</span> tag will be replaced by the "<strong>Import libaries</strong>" content;</li><li>The <span style="color: red;">&lt;lips-preconfig-code/&gt;</span> tag will be replace by the "<strong>Code complete</strong>" content;</li><li>The <span style="color: red;">&lt;lips-preconfig-tests/&gt;</span> tag will be replaced by the "<strong>Unit tests</strong>" content.</li></ul>';
$string['administration_language_code_success'] = 'The base code has been modified';
$string['administration_language_code_imports_error'] = 'You can put only one &lt;lips-preconfig-import/&gt; tag';
$string['administration_language_code_code_error'] = 'You can put only one &lt;lips-preconfig-code/&gt; tag';
$string['administration_language_code_tests_error'] = 'You can put only one &lt;lips-preconfig-tests/&gt; tag';
$string['administration_language_form_select'] = 'Programming language';
$string['administration_language_form_select_error'] = 'You must select a language';
$string['administration_language_form_highlighting_select'] = 'Syntax highlighting';
$string['administration_language_form_highlighting_select_error'] = 'You must select a syntax highlighting';
$string['administration_language_form_file'] = 'Select the picture';
$string['administration_language_form_file_error'] = 'You must select a picture';
$string['administration_existing_problems'] = 'Warning, some problems already exists in this course, the configuration can cause errors in the presents problems.';
$string['administration_no_syntax_highlighting'] = 'There is no syntax highlighting defined. Go to <strong>Administration > Language > Configure language</strong>.';
$string['administration_no_compile_language'] = 'There is no compile language defined. Go to <strong>Administration > Language > Configure language</strong>.';

// Category
$string['administration_category_create_title'] = 'Create a category';
$string['administration_category_create_success'] = 'The category has been created';
$string['administration_category_modify_title'] = 'Modify a category';
$string['administration_category_modify_select'] = 'Select the category';
$string['administration_category_modify_select_error'] = 'You must select a category';
$string['administration_category_modify_success'] = 'The category has been modified';
$string['administration_category_delete_title'] = 'Delete a category';
$string['administration_category_delete_info'] = 'Only empty categories can be deleted';
$string['administration_category_msg'] = 'Choose either a link to an external documentation or a textual documentation. <strong>You can\'t choose both</strong>.';
$string['administration_category_name_error'] = 'You must enter a category name';
$string['administration_category_already_exists'] = 'This category name already exists, please choose an other';
$string['administration_category_documentation_link_placeholder'] = 'Link to an external documentation';
$string['administration_category_documentation_text_placeholder'] = 'Textual documentation';
$string['administration_category_documentation_link'] = 'Documentation (Link)';
$string['administration_category_documentation_text'] = 'Documentation (Text)';
$string['administration_category_documentation_error'] = 'You can\'t have both documentations, please choose either a link to an external documentation or a textual documentation.';
$string['administration_delete_category_confirmation'] = "Confirm the deletion of the category";

// Problem
$string['administration_problem_create_title'] = 'Create a problem';
$string['administration_problem_modify_title'] = 'Modify a problem';
$string['administration_problem_create_preconfig_subtitle'] = '1. Pre-configuration';
$string['administration_problem_create_informations_subtitle'] = "2. Informations about the problem";
$string['administration_problem_create_informations_msg'] = 'Select the category of the problem.<br/>Enter the name, the difficulty, the conditions need to resolve the problem.';
$string['administration_problem_create_subject_subtitle'] = '3. Subject';
$string['administration_problem_create_subject_msg'] = 'Write the problem subject and tips';
$string['administration_problem_create_code_subtitle'] = '4. Code';
$string['administration_problem_create_code_msg'] = 'Start to import all the librairies needed for the problem resolution.<br/><br/>Then write the code that the user will complete.<br/>To define editable areas, use the tag <span style="color: red;">&lt;lips-code/&gt;</span>.<br/><br/>To finish, ';
$string['administration_problem_create_code_import_label'] = 'Import librairies';
$string['administration_problem_create_code_complete_label'] = 'Code to complete';
$string['administration_problem_create_code_unittest_label'] = 'Unit tests';
$string['administration_delete_problem_confirmation'] = "Confirm the deletion of the problem";
$string['administration_language_form_select_category_error'] = 'You must select a category';
$string['administration_language_form_select_difficulty_error'] = 'You must select a difficulty';
$string['administration_language_form_select_name_error'] = 'You must enter a name';
$string['administration_language_form_select_subject_error'] = 'You must enter a subject';
$string['administration_language_form_select_unittests_error'] = 'You must enter unit tests';
$string['administration_problem_create_code_import_label'] = 'Import librairies';
$string['administration_problem_create_code_complete_label'] = 'Code to complete';
$string['administration_problem_create_code_unittest_label'] = 'Unit tests';
$string['administration_delete_problem_confirmation'] = "Confirm the deletion of the problem";
$string['administration_problem_create_success'] = 'The problem has been created';
$string['administration_problem_modify_select'] = 'Select the problem';
$string['administration_problem_delete_title'] = 'Delete a problem';
$string['administration_problem_modify_success'] = 'The problem has been modified';


$string['administration_problems_import_title'] = 'Import problems into LIPS';
$string['administration_problems_export_title'] = 'Export problems from LIPS';
$string['administration_problem_import_success'] = 'Problems have been correctly imported';
$string['administration_problem_export_success'] = 'Problems have been correctly exported';

// My problems
$string['administration_my_problems_title'] = "My problems";

/*--------------------------------------------------
 * Web services
 *------------------------------------------------*/

$string['web_service_communication_error'] = 'An error occured when contacting the web service.<br/>You can\'t select a programming language.';

/*--------------------------------------------------
 * Problems
 *------------------------------------------------*/

$string['problem_author'] = 'Author';
$string['problem_date_creation'] = 'Date of creation';
$string['problem_nb_resolutions'] = 'Number of resolutions';
$string['problem_resolved_by'] = 'Solved by';
$string['problem_owner'] = 'You create this problem';
$string['problem_testing_picture'] = 'Problem in testing mode';