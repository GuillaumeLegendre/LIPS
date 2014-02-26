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

require_once(dirname(__FILE__) . '/page_view.php');

/**
 * Page to list the categories
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_list_categories extends page_view {

    /**
     * page_list_categories constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "problems");
    }

    /**
     * Display the page_list_categories content
     */
    function display_content() {
        global $CFG;
        require_once("$CFG->libdir/tablelib.php");
        require_once(dirname(__FILE__) . '/../tables/categories_table.php');

        $table = new categories_table($this->cm);
        $table->out(10, true);
    }
}

/**
 * Category content
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_category extends page_view {
    private $id;

    /**
     * page_category constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm, $id) {
        parent::__construct($cm, "category");

        $this->id = $id;
    }

    /**
     * Display the page_category content
     */
    function display_content() {

        require_once(dirname(__FILE__) . '/../tables/problems_table.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_search_form.php');

        // Category documentation
        $categorydetails = get_category_details($this->id);
        echo $this->lipsoutput->display_documentation($categorydetails);

        // Category name
        echo $this->lipsoutput->display_h1($categorydetails->category_name);

        // Search form
        $array = array(
            "placeholder" => get_string('problem', 'lips'),
            "class" => "category_problems_ac"
        );

        echo '<input type="hidden" id="hiddenCategoryID" value="' . $categorydetails->id . '"/>';
        $searchform = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'categoryId' => $this->id)), $array, 'post', '', array('class' => 'search-form'));
        $searchform->display();

        $search = null;
        if ($searchform->is_submitted()) {
            $data = $searchform->get_submitted_data();
            if (!empty($data->inputSearch)) {
                $search = $data->inputSearch;
            }
        }

        if (has_role('administration')) {
            echo '<p><span style="color: red;">*</span> : ' . get_string('problem_owner', 'lips') . '.</p>';
            echo '<img src="images/' . get_string('picture_testing', 'lips') . '" width="16px" height="16px"/> : ' . get_string('problem_testing_picture', 'lips') . '.';
        }

        // Problems table
        $table = new problems_table($this->cm, $categorydetails->id, $search);
        $table->out(10, true);
    }
}

/**
 * Display the documentation of a category
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_category_documentation extends page_view {
    private $id;

    /**
     * page_documentation constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm, $id) {
        parent::__construct($cm, "categoryDocumentation");
        $this->id = $id;
    }

    /**
     * Display the category_documentation content
     */
    function display_content() {
        $details = get_category_details($this->id);
        echo $this->lipsoutput->display_h2($details->category_name . " - " . get_string('documentation', 'lips'));
        echo $details->category_documentation;
    }
}

/**
 * Display a message of confirmation for the deletion of a category.
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_delete_category extends page_view {
    private $id;
    private $originv;
    private $originaction;

    function  __construct($cm, $id, $originv, $originaction) {
        parent::__construct($cm, "deleteCategory");

        $this->id = $id;
        $this->originv = $originv;
        $this->originaction = $originaction;
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        $details = get_category_details($this->id);
        $message = $this->lipsoutput->display_h2(get_string('administration_delete_category_confirmation', 'lips') . " " . $details->category_name . " ?");

        $continueurl = new moodle_url('action.php', array('id' => $this->cm->id, 'action' => $this->view, 'originV' => $this->originv, 'originAction' => $this->originaction, 'categoryId' => $this->id));
        if ($this->originaction != null) {
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv, 'action' => $this->originaction));
        } else {
            $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->originv));
        }
        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}
