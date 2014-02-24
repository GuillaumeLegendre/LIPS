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
$string['modulename_help'] = 'The activity module LIPS permit for a .';
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
$string['followed_users_table'] = 10;
$string['notifications_limit'] = 50;
$string['challenges_table'] = 10;

/*--------------------------------------------------
 * Status
 *------------------------------------------------*/
$string['coursecreator'] = 'Course creator';
$string['teacher'] = 'Teacher';
$string['student'] = 'Student';

/*--------------------------------------------------
 * Pictures
 *------------------------------------------------*/
$string['picture_default_language'] = 'default_language.png';
$string['picture_test'] = 'test.png';
$string['picture_testing'] = 'testing.png';

/*--------------------------------------------------
 * Difficulties
 *------------------------------------------------*/
$string['difficulty_label_elementary'] = 'Elementary';
$string['difficulty_label_easy'] = 'Easy';
$string['difficulty_label_medium'] = 'Medium';
$string['difficulty_label_difficult'] = 'Difficult';

/*--------------------------------------------------
 * Notifications
 *------------------------------------------------*/

// Text
$string['notification_follow'] = '{img} {date} - {notification_from} is following {notification_to}';
$string['notification_followed'] = '{img} {date} - {notification_from} has added you to his/her following list';
$string['notification_challenge'] = '{img} {date} - {notification_from} has challenged {notification_to} on the problem {notification_problem}';
$string['notification_challenge_accepted'] = '{img} {date} - {notification_from} has accepted the challenge on the problem {notification_problem} launched by{notification_to}';
$string['notification_challenge_refused'] = '{img} {date} - {notification_from} declined the challenge on the problem {notification_problem} launched by {notification_to}';
$string['notification_problem_solved'] = '{img} {date} - {notification_from} solved the problem {notification_problem}';
$string['notification_problem_created'] = '{img} {date} - {notification_from} created the problem {notification_problem}';
$string['notification_problem_modified'] = '{img} {date} - {notification_from} modified the problem {notification_problem}';
$string['notification_problem_deleted'] = '{img} {date} - {notification_from} deleted the problem {notification_text}';
$string['notification_category_created'] = '{img} {date} - {notification_from} created the cateogry {notification_category}';
$string['notification_category_modified'] = '{img} {date} - {notification_from} modified the category {notification_category}';
$string['notification_category_deleted'] = '{img} {date} - {notification_from} deleted the category {notification_text}';
$string['notification_grade'] = '{img} {date} - {notification_from} as won the grade {notification_text}';

// Picture
$string['notification_follow_picture'] = 'follow.png';
$string['notification_followed_picture'] = 'follow.png';
$string['notification_challenge_picture'] = 'challenge.png';
$string['notification_challenge_accepted_picture'] = 'challenge-accepted.png';
$string['notification_challenge_refused_picture'] = 'challenge-refused.png';
$string['notification_problem_solved_picture'] = 'solved.png';
$string['notification_problem_created_picture'] = 'add.png';
$string['notification_problem_modified_picture'] = 'edit.png';
$string['notification_problem_deleted_picture'] = 'delete.png';
$string['notification_category_created_picture'] = 'add.png';
$string['notification_category_modified_picture'] = 'edit.png';
$string['notification_category_deleted_picture'] = 'delete.png';
$string['notification_grade_picture'] = 'grade.png';

/*--------------------------------------------------
 * Challenges
 *------------------------------------------------*/
$string['challenge_notification'] = '{date} - {challenge_from} has challenged you on the problem {challenge_problem}';
$string['challenge_current'] = '{challenge_problem} challenged by {challenge_from}';
$string['received_challenges'] = 'Received challenges';
$string['sent_challenges'] = 'Sent challenges';
$string['challenge_challenged'] = 'Challenged';
$string['state'] = 'State';
$string['administration_cancel_challenge_confirmation'] = 'Are you sure to cancel the challenge ?';
$string['WAITING'] = 'Waiting';
$string['ACCEPTED'] = 'Accepted';
$string['SOLVED'] = 'Solved';
$string['REFUSED'] = 'Refused';

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
$string['error_impossible'] = 'You shouldn\'t have access to this page';
$string['language'] = 'Language';
$string['follow'] = 'Follow';
$string['unfollow'] = 'Unfollow';
$string['user'] = 'User';
$string['grade'] = 'Grade';
$string['ranks'] = 'Ranks';
$string['status'] = 'Status';
$string['solved_problems'] = 'Solved problems';
$string['challenges'] = 'Challenges';
$string['challenge'] = 'Challenge';
$string['challenged'] = 'Challenged';
$string['challenged_users'] = 'Challenged users';
$string['no_challenges'] = 'No challenges';
$string['current_challenges'] = 'Current challenges';
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
$string['testing'] = 'Test mode';
$string['recent_activity'] = 'Recent activity';
$string['achievements'] = 'Achievements';
$string['at'] = '\a\t';
$string['notifications'] = 'Notifications';
$string['no_notifications'] = 'No notification';
$string['refuse'] = 'Refuse';
$string['solve'] = 'Solve';
$string['accept'] = 'Accept';
$string['Rank'] = 'Rank';
$string['filter'] = 'Filter';
$string['cancel'] = 'Cancel';
$string['select'] = 'Select';
$string['send_message'] = 'Send a message';
$string['unranked'] = 'Unranked';

/*--------------------------------------------------
 * Dates
 *------------------------------------------------*/

// Months
$string['Jan'] = 'January';
$string['Feb'] = 'February';
$string['Mar'] = 'March';
$string['Apr'] = 'April';
$string['May'] = 'May';
$string['Jun'] = 'June';
$string['Jul'] = 'July';
$string['Aug'] = 'August';
$string['Sep'] = 'September';
$string['Oct'] = 'October';
$string['Nov'] = 'November';
$string['Dec'] = 'December';

// Days
$string['Mon'] = 'Monday';
$string['Tue'] = 'Tuesday';
$string['Wed'] = 'Wednesday';
$string['Thu'] = 'Thursday';
$string['Fri'] = 'Friday';
$string['Sat'] = 'Saturday';
$string['Sun'] = 'Sunday';

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
$string['challenge_author'] = 'Author';
$string['resolve'] = 'Resolve';

/*--------------------------------------------------
 * Administration
 *------------------------------------------------*/

// Language
$string['administration_language_configure_title'] = 'Configure language';
$string['administration_language_configure_msg'] = 'Select the language to work with.<br/>To give an indication to the student on the code he will implement, you can specify a comment format (e.g: /* TODO */) apdated to the language.';
$string['administration_language_configure_success'] = 'The language has been configured';
$string['administration_language_image_title'] = 'Picture';
$string['administration_language_image_msg'] = 'Choose the picture for this programming language.<br/>This one will be used at the rank display.';
$string['administration_language_image_success'] = 'The picture has been modified';
$string['administration_language_image_save_error'] = 'An error occured while saving the file. Please try again.';
$string['administration_language_image_type_error'] = 'The file you chose is not a picture. Please upload a picture.';
$string['administration_language_code_title'] = 'Base code';
$string['administration_language_code_msg'] = 'Enter the base  code used for each compilation.<br/>Before the compilation :<ul><li>The <span style="color: red;">&lt;lips-preconfig-import/&gt;</span> tag will be replaced by the "<strong>Import libaries</strong>" content;</li><li>The <span style="color: red;">&lt;lips-preconfig-code/&gt;</span> tag will be replace by the "<strong>Code complete</strong>" content;</li><li>The <span style="color: red;">&lt;lips-preconfig-tests/&gt;</span> tag will be replaced by the "<strong>Unit tests</strong>" content.</li></ul>';
$string['administration_language_code_success'] = 'The base code has been modified';
$string['administration_language_code_imports_error'] = 'You can put only one &lt;lips-preconfig-import/&gt; tag';
$string['administration_language_code_imports_error_no'] = 'The &lt;lips-preconfig-import/&gt; tag has to be present';
$string['administration_language_code_code_error'] = 'You can put only one &lt;lips-preconfig-code/&gt; tag';
$string['administration_language_code_code_error_no'] = 'The &lt;lips-preconfig-code/&gt; tag has to be present';
$string['administration_language_code_tests_error'] = 'You can put only one &lt;lips-preconfig-tests/&gt; tag';
$string['administration_language_code_tests_error_no'] = 'The &lt;lips-preconfig-tests/&gt; tag has to be present';
$string['administration_language_form_select'] = 'Programming language';
$string['administration_language_form_select_error'] = 'You must select a language';
$string['administration_language_form_highlighting_select'] = 'Syntax highlighting';
$string['administration_language_form_highlighting_select_error'] = 'You must select a syntax highlighting mode';
$string['administration_language_form_input'] = 'Comment format';
$string['administration_language_form_file'] = 'Link to the picture';
$string['administration_language_form_file_error'] = 'You must select a picture';
$string['administration_existing_problems'] = 'Warning, some problems already exist in this course, the configuration can cause errors in the current problems.';
$string['administration_no_syntax_highlighting'] = 'There is no syntax highlighting defined. Go to <strong>Administration > Language > Configure language</strong>.';
$string['administration_no_compile_language'] = 'There is no compile language defined. Go to <strong>Administration > Language > Configure language</strong>.';
$string['administration_warning_existing_language'] = 'Only languages that are not used by other instances are displayed here.<br/>For your information these languages are already used :';


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
$string['administration_category_documentation_error'] = 'You can\'t have both documentations, please choose either a link to an external documentation or a text documentation.';
$string['administration_delete_category_confirmation'] = "Confirm the deletion of the category";
$string['administration_empty_categories'] = "No category";
$string['problemNotSelected'] = "No problem selected";

// Problem
$string['administration_problem_create_title'] = 'Create a problem';
$string['administration_problem_modify_title'] = 'Modify a problem';
$string['administration_problem_create_preconfig_subtitle'] = '1. Pre-configuration';
$string['administration_problem_create_informations_subtitle'] = "2. Problem's information";
$string['administration_problem_create_informations_msg'] = 'Select the problem\'s category.<br/>Enter the name, the difficulty, the conditions need to resolve the problem.';
$string['administration_problem_create_subject_subtitle'] = '3. Subject';
$string['administration_problem_create_subject_msg'] = 'Write the problem subject and tips';
$string['administration_problem_create_code_subtitle'] = '4. Code';
$string['administration_problem_create_code_msg'] = 'Start to import all the librairies needed for the problem resolution.<br/><br/>Then write the code that the user will complete.<br/>To define areas to edit, use the comment button to set an indication.<br/><br/>To finish, ';
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
$string['administration_delete_problem_confirmation'] = "Confirm the problem's deletion";
$string['administration_problem_create_success'] = 'The problem has been created';
$string['administration_problem_modify_select'] = 'Select the problem';
$string['administration_problem_delete_title'] = 'Delete a problem';
$string['administration_problem_modify_success'] = 'The problem has been modified';
$string['administration_problem_similar_subtitle'] = '5. Advice similar problems';
$string['administration_problem_similar_subtitle_msg'] = 'Advice some problems the user might want to realise after this one.';
$string['administration_problems_import_confirmation'] = "You will be redirect on the Moodle page to restore a course. It will allow you to import problems into LIPS.";
$string['administration_problems_export_confirmation'] = "You will be redirect on the Moodle page to backup a course. It will allow you to export problems from LIPS.";
$string['administration_language_form_code_error'] = 'You must enter the code';
$string['administration_unittests_form_code_error'] = 'You must enter the unit tests';
$string['administration_problem_already_exists'] = 'This problem name already exists, please choose an other';
$string['administration_delete_problems_confirmation'] = 'Confirm the deletion of these problems ?';
$string['administration_delete_problem_confirmation_msg'] = 'Confirm the deletion of this problem ?';
$string['administration_empty_problems'] = 'No problems';
$string['administration_unittests_form_code_unvalid'] = 'You must display keywords PROBLEM_SOLVED and PROBLEM_FAILED in unit tests';


// My problems
$string['administration_my_problems_title'] = "My problems";

/*--------------------------------------------------
 * Web services
 *------------------------------------------------*/

$string['web_service_communication_error'] = 'An error occured while contacting the web service.<br/>You can\'t select a programming language.';
$string['web_service_compil_communication_error'] = 'An error occured while contacting the web service.<br/>You can\'t send your solution for the moment.';


/*--------------------------------------------------
 * Problems
 *------------------------------------------------*/

$string['problem_author'] = 'Author';
$string['problem_date_creation'] = 'Creation date';
$string['problem_nb_resolutions'] = 'Number of resolutions';
$string['problem_resolved_by'] = 'Solved by';
$string['problem_owner'] = 'You created this problem';
$string['problem_testing_picture'] = 'Problem in testing mode';
$string['problem_testing_info'] = 'This problem is in <strong>Testing</strong> mode. You can go to <strong>Display</strong> mode by going in <strong>Administration > Problems > My problems</strong>.';
$string['problem_challenge_success'] = 'The selected users has been challenged';
$string['problem_solved_success'] = 'Congratulation you have solved the problem !';
$string['problem_solved_fail'] = 'Insert coin';
$string['problem_challenge_success'] = 'The selected users have been challenged';