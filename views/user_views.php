<?php

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
    function  __construct($cm) {
        parent::__construct($cm, "users");
    }

    /**
     * Display the page_users content
     */
    function display_content() {
        global $CFG, $PAGE;

        require_once(dirname(__FILE__) . '/../tables/users_table.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_search_form.php');

        // Users title
        echo $this->lipsoutput->display_h1(get_string('users', 'lips'));

        $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view)), null, 'post', '', array('class' => 'search-form'));
        $searchForm->display();

        $search = null;
        if ($searchForm->is_submitted()) {
            $data = $searchForm->get_submitted_data();
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
    function  __construct($cm) {
        parent::__construct($cm, "rank");
    }

    /**
     * Display the page_rank content
     */
    function display_content() {
        global $CFG;
        require_once("$CFG->libdir/tablelib.php");
        require_once(dirname(__FILE__) . '/../tables/rank_table_flexible.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_filter_form.php');

        // Rank title
        $category_id_post = optional_param("category_id_js", null, PARAM_TEXT);
        echo $this->lipsoutput->display_h1(get_string('Rank', 'lips'));
        $filterform = new mod_lips_filter_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view)), null, 'post', '', array('class' => 'search-form'));
        $usersearch = null;
        $instance_id_js = null;
        $category_id_js = null;
        if ($filterform->is_submitted()) {
            $data = $filterform->get_submitted_data();
            if (isset($data->userSearch) && !empty($data->userSearch)) {
                $usersearch = $data->userSearch;
            }
            if (isset($data->language_id_js) && $data->language_id_js != "all") {
                $instance_id_js = $data->language_id_js;
            }
            if (isset($category_id_post) && $category_id_post != "all") {
                $category_id_js = $category_id_post;
            }
        }
        $filterform->display();
        $table = new rank_table($this->cm, $usersearch, $instance_id_js, $category_id_js);
        $table->finish_output();
    }
}