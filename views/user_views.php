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
 * Page to list the users
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_users extends page_view {

    /**
     * page_users constructor
     *
     * @param object $cm Moodle context
     */
    public function  __construct($cm) {
        parent::__construct($cm, "users");
    }

    /**
     * Display the page_users content
     */
    protected function display_content() {
        global $CFG, $PAGE;

        require_once(dirname(__FILE__) . '/../tables/users_table.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_search_form.php');

        // Users title.
        echo $this->lipsoutput->display_h1(get_string('users', 'lips'));

        // Search form.
        $array = array(
            "placeholder" => get_string('user', 'lips'),
            "class" => "users_ac"
        );
        $searchform = new mod_lips_search_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view)),
            $array, 'post', '', array('class' => 'search-form'));
        $searchform->display();

        $search = null;
        if ($searchform->is_submitted()) {
            $data = $searchform->get_submitted_data();
            if (!empty($data->inputSearch)) {
                $search = $data->inputSearch;
            }
        }

        $table = new users_table($this->cm, $search);
        $table->out(get_string('users_table', 'lips'), true);
    }
}

/**
 * Page to view the global rank
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_rank extends page_view {

    /**
     * page_rank constructor
     *
     * @param object $cm Moodle context
     */
    public function  __construct($cm) {
        parent::__construct($cm, "rank");
    }

    /**
     * Display the page_rank content
     */
    protected function display_content() {
        global $CFG;
        require_once("$CFG->libdir/tablelib.php");
        require_once(dirname(__FILE__) . '/../tables/rank_table_flexible.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_filter_form.php');

        // Rank title.
        $categoryidpost = optional_param("category_id_js", null, PARAM_TEXT);
        echo $this->lipsoutput->display_h1(get_string('Rank', 'lips'));

        $array = array(
            "placeholder" => get_string('user', 'lips'),
            "class" => "users_ac"
        );
        $filterform = new mod_lips_filter_form(
            new moodle_url('view.php',
                array('id' => $this->cm->id, 'view' => $this->view)),
            $array, 'post', '', array('class' => 'search-form'));
        $usersearch = null;
        $instanceidjs = null;
        $categoryidjs = null;
        if ($filterform->is_submitted()) {
            $data = $filterform->get_submitted_data();
            if (isset($data->userSearch) && !empty($data->userSearch)) {
                $usersearch = $data->userSearch;
            }
            if (isset($data->language_id_js) && $data->language_id_js != "all") {
                $instanceidjs = $data->language_id_js;
            }
            if (isset($categoryidpost) && $categoryidpost != "all") {
                $categoryidjs = $categoryidpost;
            }
        }
        $filterform->display();
        $table = new rank_table($this->cm, $usersearch, $instanceidjs, $categoryidjs);
        $table->finish_output();
    }
}