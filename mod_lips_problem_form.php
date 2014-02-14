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

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once(dirname(__FILE__) . '/locallib.php');

/**
 * Form to create a problem
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problem_create_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE;

        $mform =& $this->_form;
        $output = $PAGE->get_renderer('mod_lips');
        // Fetch all categories.
        $categories = array();
        foreach (fetch_all_categories(get_current_instance()->id) as $category) {
            $categories[$category->id] = $category->category_name;
        }

        // Fetch all difficulties.
        $difficulties = array();
        foreach (fetch_all_difficulties() as $difficulty) {
            $difficulties[$difficulty->id] = get_string($difficulty->difficulty_label, "lips");
        }

        // /!\ DO NOT DELETE.
        $mform->addElement('select', 'correction', null);

        // Preconfig.
        $lips = get_current_instance();
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_preconfig_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_language_code_msg", "lips"));

        if($lips->base_code != null) {
            $mform->addElement('html', '<div id="preconfigEditor" class="ace" style="margin: auto;">' . htmlspecialchars($lips->base_code) . '</div>');
        }

        // Global Informations.
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_informations_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_informations_msg", "lips"));

        $mform->addElement('select', 'problem_category_id', get_string('category', 'lips'), $categories);
        $mform->addRule('problem_category_id', get_string('administration_language_form_select_category_error', 'lips'), 'required', null, 'client');

        $mform->addElement('select', 'problem_difficulty_id', get_string('difficulty', 'lips'), $difficulties);
        $mform->addRule('problem_difficulty_id', get_string('administration_language_form_select_difficulty_error', 'lips'), 'required');

        $mform->addElement('text', 'problem_label', get_string('problem', 'lips'), array('size' => '64', 'maxlength' => '255', 'placeholder' => get_string('name', 'lips')));
        $mform->addRule('problem_label', get_string('administration_language_form_select_name_error', 'lips'), 'required', null, 'client');
        $mform->setType('problem_label', PARAM_TEXT);

        $mform->addElement('text', 'problem_preconditions', get_string('prerequisite', 'lips'), array('size' => '64', 'maxlength' => '255'));
        $mform->setType('problem_preconditions', PARAM_TEXT);

        // Subject.
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_subject_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_subject_msg", "lips"));

        $mform->addElement('editor', 'problem_statement', get_string("subject", "lips"));
        $mform->addRule('problem_statement', get_string('administration_language_form_select_subject_error', 'lips'), 'required', null, 'client');
        $mform->addElement('editor', 'problem_tips', get_string("tips", "lips"));

        // Code.
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_code_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_code_msg", "lips"));

        // Textarea for the imports.
        $mform->addElement('html', '<p class="acetitle">' . get_string("administration_problem_create_code_import_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="importsEditor" class="ace"></div>');
        $mform->addElement('textarea', 'problem_imports', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));

        // Textarea for the code to complete
        $mform->addElement('html', '<p class="acetitle required">' . get_string("administration_problem_create_code_complete_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="problemCodeEditor" class="ace"></div>');
        $mform->addElement('textarea', 'problem_code', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));

        // Textarea for the unit tests
        $mform->addElement('html', '<p class="acetitle required">' . get_string("administration_problem_create_code_unittest_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="unitTestsEditor" class="ace"></div>');
        $mform->addElement('textarea', 'problem_unit_tests', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));

        /*--------------------------------------------------
        * Similar problems
        *------------------------------------------------*/
        $categorieswithproblems = array();
        foreach (fetch_all_categories_with_problems() as $category) {
            $categorieswithproblems[$category->id] = $category->category_name;
        }

        $problems = array();
        foreach (fetch_problems_by_category(key($categorieswithproblems)) as $problem) {
            $problems[$problem->id] = $problem->problem_label;
        }

        // Hidden field to store id of similar problems.
        $mform->addElement('hidden', 'problems_similar', null, array("id" => "id_problem_similar"));
        $mform->setType('problems_similar', PARAM_TEXT);
        $mform->addElement('html', '<div id="dialog" title="Conseil de problèmes similaires"><h2>Conseil - ' . $lips->compile_language . '</h2>');
        $mform->addElement('select', 'problem_category_id_js', get_string('category', 'lips'),
            $categorieswithproblems, array('class' => 'text ui-widget-content ui-corner-all', 'style' => 'width:95%'));
        $mform->addElement('select', 'problem_id_js', get_string('problem', 'lips'),
            $problems, array('class' => 'text ui-widget-content ui-corner-all', 'style' => 'width:95%'));
        $mform->addElement('html', '</div>');

        $mform->addElement('html', $output->display_h3(get_string("administration_problem_similar_subtitle", "lips")));
        $mform->addElement('html', $output->display_p(get_string("administration_problem_similar_subtitle_msg", "lips")));
        $mform->addElement('html', '<div id="problem_similar_content">');
        $mform->addElement('html', '</div>');
        $mform->addElement('button', 'intro',
            get_string("administration_problem_modify_select", "lips"), array('class' => 'problem_similar', 'id' => 'problem_similar_button'));

        /*--------------------------------------------------
        * Submit
        *------------------------------------------------*/

        // Create & Test button
        $mform->addElement('submit', 'submit', get_string('create', 'lips'));
        $mform->addElement('submit', 'submit', get_string('test_problem', 'lips'));
    }


    /**
     * Form custom validation
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     * @return array Errors array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        return $errors;
    }

    /**
     * Handle the form
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     */
    public function handle() {
        global $DB, $USER, $PAGE;

        // Do nothing if not submitted or cancelled.
        if (!$this->is_submitted() || $this->is_cancelled())
            return;

        // Form data.
        $data = $this->get_submitted_data();

        // The validation failed.
        $errors = $this->validation($data, null);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo $PAGE->get_renderer('mod_lips')->display_notification($error, 'ERROR');
            }
            return;
        }

        $problemssimilarid = array_unique(explode(" ", $data->problems_similar));
        // Insert the problem
        $data->problem_date = time();
        $data->problem_creator_id = $USER->id;
        $data->problem_statement = $data->problem_statement['text'];
        $data->problem_tips = $data->problem_tips['text'];
        if ($data->submit == get_string('test_problem', 'lips')) {
            $data->problem_testing = 1;
        }
        $problemid = $DB->insert_record('lips_problem', $data);
        foreach ($problemssimilarid as $problemsimilar) {
            if (!empty($problemsimilar)) {
                insert_problem_similar($problemid, $problemsimilar);
            }
        }
        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_problem_create_success', 'lips'), 'SUCCESS');
    }
}

/**
 * Form to delete a problem
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problem_delete_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE, $USER;
        $mform =& $this->_form;
        $problems = "";
        foreach (fetch_problems($USER->id) as $problem) {
            $problems[$problem->id] = $problem->problem_label;
        }
        $mform->addElement('select', 'problemId', get_string('administration_problem_modify_select', 'lips'), $problems);
        // Delete button.
        $mform->addElement('submit', 'submit', get_string('delete', 'lips'));
    }
}

/**
 * Form to modify a problem
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickael OHLEN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problem_modify_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE;
        $mcustomdata = $this->_customdata;
        $mform =& $this->_form;
        $output = $PAGE->get_renderer('mod_lips');
        // Fetch all categories
        $categories = array();
        foreach (fetch_all_categories(get_current_instance()->id) as $category) {
            $categories[$category->id] = $category->category_name;
        }

        // Fetch all difficulties
        $difficulties = array();
        foreach (fetch_all_difficulties() as $difficulty) {
            $difficulties[$difficulty->id] = get_string($difficulty->difficulty_label, "lips");
        }
        // ID.
        $mform->addElement('hidden', 'id_problem', null, null);
        $mform->setType('id_problem', PARAM_INT);
        $mform->setDefault('id_problem', $mcustomdata['id']);

        // Preconfig
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_preconfig_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_language_code_msg", "lips"));

        // Global Informations
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_informations_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_informations_msg", "lips"));

        $mform->addElement('select', 'problem_category_id', get_string('category', 'lips'), $categories)->setSelected($mcustomdata['problem_category_id']);
        $mform->addRule('problem_category_id', get_string('administration_language_form_select_category_error', 'lips'), 'required', null, 'client');

        $mform->addElement('select', 'problem_difficulty_id', get_string('difficulty', 'lips'), $difficulties)->setSelected($mcustomdata['difficulty_id']);
        $mform->addRule('problem_difficulty_id', get_string('administration_language_form_select_difficulty_error', 'lips'), 'required');

        $mform->addElement('text', 'problem_label', get_string('problem', 'lips'), array('size' => '64', 'maxlength' => '255', 'placeholder' => get_string('name', 'lips')));
        $mform->addRule('problem_label', get_string('administration_language_form_select_name_error', 'lips'), 'required', null, 'client');
        $mform->setType('problem_label', PARAM_TEXT);
        $mform->setDefault('problem_label', $mcustomdata['problem_label']);

        $mform->addElement('text', 'problem_preconditions', get_string('prerequisite', 'lips'), array('size' => '64', 'maxlength' => '255'));
        $mform->setType('problem_preconditions', PARAM_TEXT);
        $mform->setDefault('problem_preconditions', $mcustomdata['problem_preconditions']);

        // Subject
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_subject_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_subject_msg", "lips"));

        $mform->addElement('editor', 'problem_statement', get_string("subject", "lips"))->setValue(array('text' => $mcustomdata['problem_statement']));
        $mform->addRule('problem_statement', get_string('administration_language_form_select_subject_error', 'lips'), 'required', null, 'client');
        $mform->addElement('editor', 'problem_tips', get_string("tips", "lips"))->setValue(array('text' => $mcustomdata['problem_tips']));

        // Code
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_create_code_subtitle", "lips")));
        $mform->addElement('html', get_string("administration_problem_create_code_msg", "lips"));

        // Textarea for the imports
        $mform->addElement('html', '<p class="acetitle">' . get_string("administration_problem_create_code_import_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="importsEditor" class="ace">' . htmlspecialchars($mcustomdata['problem_imports']) . '</div>');
        $mform->addElement('textarea', 'problem_imports', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));
        $mform->setDefault('problem_imports', $mcustomdata['problem_imports']);

        // Textarea for the code to complete
        $mform->addElement('html', '<p class="acetitle required">' . get_string("administration_problem_create_code_complete_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="problemCodeEditor" class="ace">' . htmlspecialchars($mcustomdata['problem_code']) . '</div>');
        $mform->addElement('textarea', 'problem_code', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));
        $mform->setDefault('problem_code', $mcustomdata['problem_code']);

        // Textarea for the unit tests
        $mform->addElement('html', '<p class="acetitle required">' . get_string("administration_problem_create_code_unittest_label", "lips") . '</p>');
        $mform->addElement('html', '<div id="unitTestsEditor" class="ace">' . htmlspecialchars($mcustomdata['problem_unit_tests']) . '</div>');
        $mform->addElement('textarea', 'problem_unit_tests', null, array('rows' => 1, 'cols' => 1, 'class' => 'editorCode'));
        $mform->setDefault('problem_unit_tests', $mcustomdata['problem_unit_tests']);

        /*--------------------------------------------------
       * Similar problems
       *------------------------------------------------*/
        $lips = get_current_instance();
        $categorieswithproblems = array();
        foreach (fetch_all_categories_with_problems() as $category) {
            $categorieswithproblems[$category->id] = $category->category_name;
        }
        $problems = array();
        foreach (fetch_problems_by_category(key($categorieswithproblems)) as $problem) {
            $problems[$problem->id] = $problem->problem_label;
        }
        // Hidden field to store id of similar problems.
        $mform->setType('problems_similar', PARAM_TEXT);
        $mform->addElement('html', '<div id="dialog" title="Conseil de problèmes similaires"><h2>Conseil - ' . $lips->compile_language . '</h2>');
        $mform->addElement('select', 'problem_category_id_js', get_string('category', 'lips'),
            $categorieswithproblems, array('class' => 'text ui-widget-content ui-corner-all', 'style' => 'width:95%'));
        $mform->addElement('select', 'problem_id_js', get_string('problem', 'lips'),
            $problems, array('class' => 'text ui-widget-content ui-corner-all', 'style' => 'width:95%'));
        $mform->addElement('html', '</div>');
        $mform->addElement('html', $output->display_h3(get_string("administration_problem_similar_subtitle", "lips")));
        $mform->addElement('html', '<div id="problem_similar_content">');
        $mform->addElement('html', '</div>');

        $idproblem = $mcustomdata['id'];
        $valuehiddenfield = "";
        if (isset($idproblem)) {
            $similarproblems = get_similar_problems($mcustomdata['id']);
            foreach ($similarproblems as $similarproblem) {
                $mform->addElement('text', 'problem_similar_show_'.$similarproblem->problem_similar_id, null, array('readonly'));
                $mform->setDefault('problem_similar_show_'.$similarproblem->problem_similar_id, $similarproblem->problem_label);
                $mform->setType('problem_similar_show_'.$similarproblem->problem_similar_id, PARAM_TEXT);
                $valuehiddenfield .= " " . $similarproblem->problem_similar_id;
            }
        }
        $mform->addElement('hidden', 'problems_similar', $valuehiddenfield, array("id" => "id_problem_similar"));
        $mform->addElement('button', 'intro',
            get_string("administration_problem_modify_select", "lips"), array('class' => 'problem_similar', 'id' => 'problem_similar_button'));
        // Create button
        $mform->addElement('submit', 'submit', get_string('edit', 'lips'));
    }

    /**
     * Form custom validation
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     * @return array Errors array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }

    /**
     * Handle the form
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     */
    public function handle($instance) {
        global $USER, $PAGE;
        // Do nothing if not submitted or cancelled.
        if (!$this->is_submitted() || $this->is_cancelled()) {
            return;
        }
        // Form data.
        $data = $this->get_submitted_data();
        // The validation failed.
        $data->id = $data->id_problem;
        $errors = $this->validation($data, null);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo $PAGE->get_renderer('mod_lips')->display_notification($error, 'ERROR');
            }
            return;
        }
        $data->problem_date = time();
        $data->problem_creator_id = $USER->id;
        $data->problem_statement = $data->problem_statement['text'];
        $data->problem_tips = $data->problem_tips['text'];
        update_problem($data);

        $problemssimilarid = array_unique(explode(" ", $data->problems_similar));
        foreach ($problemssimilarid as $problemsimilar) {
            if (!empty($problemsimilar)) {
                if (!problem_similar_exist($data->id, $problemsimilar)) {
                    insert_problem_similar($data->id, $problemsimilar);
                }
            }
        }


        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_problem_modify_success', 'lips'), 'SUCCESS');
    }
}

/**
 * Form to select the category to modify
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin GOT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problem_modify_select_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $USER;
        $mform =& $this->_form;

        // Select the category.
        $lips = get_current_instance();
        $problems = array();
        foreach (fetch_problems($USER->id) as $problem) {
            $problems[$problem->id] = $problem->problem_label;
        }
        $mform->addElement('select', 'problemId', get_string('administration_problem_modify_select', 'lips'), $problems);
        $mform->addRule('problemId', get_string('administration_category_modify_select_error', 'lips'), 'required', null, 'client');

        // Modify button.
        $mform->addElement('submit', 'submit', get_string('modify', 'lips'));
    }
}

/**
 * Form to import problems
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaïs PICOREAU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problems_import_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE, $USER, $CFG;
        $mform =& $this->_form;
        // $context = $PAGE->context;
        // $coursecontext = $context->get_course_context();
        // $courseid = $coursecontext->instanceid;

        $warning = get_string('administration_problem_import_warning_msg', 'lips') . $CFG->dataroot . "/temp/backup/";
        echo $PAGE->get_renderer('mod_lips')->display_notification($warning, 'WARNING');

        $mform->addElement('text', 'backupFile', "Choix du répertoire d'import");
        $mform->setType('backupFile', PARAM_TEXT);

        // $contextid = $context->id;
        // $component = "user";
        // $filearea = "draft";
        // $itemid = 0;
        // $filepath = "/";
        // $filename = "Simple.txt";
        // $filecontent = base64_encode("Let us create a nice simple file");
        // $contextlevel = null;
        // $instanceid = null;

        // $mform->addElement('file', 'backupFile', get_string('file'), null,
        //            array('maxbytes' => $CFG->maxbytes, 'accepted_types' => '*'));

        // $mform->addElement('filepicker', 'backupFile', "Upload a Document", null, array('maxbytes' => 1024*1024, 'accepted_types' =>array('*.png', '*.jpg', '*.gif','*.jpeg', '*.doc', '*.rtf','*.pdf','*.txt')));

        // $mform->addElement('filemanager', 'backupFile', "Upload a file to import", null,
        //             array('subdirs' => 1, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => 1,
        //                   'accepted_types' => array('*.mbz')));

        // $fileinfo = $browser->get_file_info($contextid, $component, "user_area", $itemid, $filepath, $filename);

        // $fs = get_file_storage();
        // $browser = get_file_browser();
        // $fileinfo = $browser->get_file_info($contextid, $component, $filearea, $itemid, $filepath, $filename)
        // $params = $fileinfo->get_params();
        // $file = $fs->get_file($params['contextid'], $params['component'], $params['filearea'],
        //                     $params['itemid'], $params['filepath'], $params['filename']);

        // $fullpath = "/var/moodledata/temp/backup";
        // if ($file = $fs->get_file_by_hash(sha1($fullpath)) and $file->is_directory()) {
        //     echo "Directory found";

        //     $viewer;
        //     $viewer->files = array($file);
        //     $viewer->currentcontext->id->$courseid = $courseid;
        //     // $html = render_backup_files_viewer($viewer);
        //     // echo $html;

        //     echo "<pre>";
        //     echo $OUTPUT->render_backup_files_viewer($viewer);
        //     echo "<pre>";
        // }

        // $contextid = $context->id;
        // $browser = get_file_browser();
        // $filepath = 

        // // check if tmp dir exists.
        // $tmpdir = $CFG->tempdir . '/backup';
        // if (!check_dir_exists($tmpdir, true, true)) {
        //     throw new restore_controller_exception('cannot_create_backup_temp_dir');
        // }

        // // Choose the backup file from backup files tree.
        // if ($action == 'choosebackupfile') {
        //     if ($fileinfo = $browser->get_file_info($filecontext, $component, $filearea, $itemid, $filepath, $filename)) {
        //         if (is_a($fileinfo, 'file_info_stored')) {

        //             // Use the contenthash rather than copying the file where possible,
        //             // to improve performance and avoid timeouts with large files.
        //             $fs = get_file_storage();
        //             $params = $fileinfo->get_params();
        //             $file = $fs->get_file($params['contextid'], $params['component'], $params['filearea'],
        //                     $params['itemid'], $params['filepath'], $params['filename']);
        //             $restore_url = new moodle_url('/backup/restore.php', array('contextid' => $contextid,
        //                     'pathnamehash' => $file->get_pathnamehash(), 'contenthash' => $file->get_contenthash()));
        //         } else {
        //             // If it's some weird other kind of file then use old code.
        //             $filename = restore_controller::get_tempdir_name($course->id, $USER->id);
        //             $pathname = $tmpdir . '/' . $filename;
        //             $fileinfo->copy_to_pathname($pathname);
        //             $restore_url = new moodle_url('/backup/restore.php', array(
        //                     'contextid' => $contextid, 'filename' => $filename));
        //         }
        //         redirect($restore_url);
        //     } else {
        //         redirect($url, get_string('filenotfound', 'error'));
        //     }
        //     die;
        // }

        // Import button.
        $mform->addElement('submit', 'submit', get_string('import', 'lips'));
    }

    /**
     * Form custom validation
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     * @return array Errors array
     */
    public function validation($data, $files) {
        global $CFG;
        $errors = parent::validation($data, $files);

        // Check a directory name has been specified.
        settype($backupFile,"string");
        $path = $data->backupFile;
        if (isset($path) && !empty($path)) {

            $dir = $CFG->dataroot . "/temp/backup/" . $data->backupFile;
            // Check the file exists.
            if (file_exists($dir)) {

                // Check it is a directory.
                if (!is_dir($dir)) {
                    $errors['notDirectory'] = get_string('administration_problem_import_directory_error', 'lips');
                }
            } else {
                $errors['notExistingFile'] = get_string('administration_problem_import_notexist_error', 'lips');
            }
        } else {
            $errors['emptyImportDirectoryName'] = get_string('administration_problem_import_empty_error', 'lips');
        }

        return $errors;
    }

    /**
     * Handle the form
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     */
    public function handle($instance) {
        global $DB, $USER, $PAGE, $CFG;
        require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

        // Do nothing if not submitted or cancelled.
        if (!$this->is_submitted() || $this->is_cancelled()) {
            return;
        }

        // Form data.
        $data = $this->get_submitted_data();

        // The validation failed.
        $errors = $this->validation($data, null);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo $PAGE->get_renderer('mod_lips')->display_notification($error, 'ERROR');
            }
            return;
        }

        // Transaction.
        $transaction = $DB->start_delegated_transaction();

        // Get current course id.
        $context = $PAGE->context;
        $coursecontext = $context->get_course_context();
        $courseid = $coursecontext->instanceid;

        // Get current user.
        $userid = $USER->id;

        // A directory existing in $CFG->dataroot . '/temp/backup/'
        $folder = $data->backupFile;

        // Restore backup into course.
        $controller = new restore_controller($folder, $courseid,
            backup::INTERACTIVE_NO, backup::MODE_GENERAL, $userid, backup::TARGET_CURRENT_ADDING);
        $controller->execute_precheck();
        $controller->execute_plan();

        // Commit.
        $transaction->allow_commit();

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_problem_import_success', 'lips'), 'SUCCESS');
    }
}

/**
 * Form to export problems
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaïs PICOREAU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_problems_export_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $PAGE, $CFG;
        $mform =& $this->_form;

        $warning = get_string('administration_problem_export_warning_msg', 'lips') . $CFG->dataroot . "/temp/backup/";
        echo $PAGE->get_renderer('mod_lips')->display_notification($warning, 'WARNING');

        // Export button.
        $mform->addElement('submit', 'submit', get_string('export', 'lips'));
    }

    /**
     * Form custom validation
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     * @return array Errors array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }

    /**
     * Handle the form
     *
     * @param array $data Form data
     * @param array $files Form uploaded files
     */
    public function handle($instance) {
        global $DB, $USER, $PAGE, $CFG;
        require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');

        // Do nothing if not submitted or cancelled.
        if (!$this->is_submitted() || $this->is_cancelled())
            return;

        // Get current module id. 
        $cm = get_coursemodule_from_id('lips', optional_param('id', 0, PARAM_INT), 0, false, MUST_EXIST);
        $moduleid = $cm->id;

        // Get current user.
        $userid = $USER->id;

        $bc = new backup_controller(backup::TYPE_1ACTIVITY, $moduleid, backup::FORMAT_MOODLE,
            backup::INTERACTIVE_YES, backup::MODE_GENERAL, $userid);
        $bc->finish_ui();
        $bc->execute_plan();
        $bc->get_results();

        // Success message.
        echo $PAGE->get_renderer('mod_lips')->display_notification(get_string('administration_problem_export_success', 'lips'), 'SUCCESS');
    }
}