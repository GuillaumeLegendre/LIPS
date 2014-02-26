<?php

require_once(dirname(__FILE__) . '/page_view.php');

/**
 * Profile page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile extends page_view {

    /**
     * page_profile constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile content
     */
    function display_content() {
        global $USER;

        $iduser = optional_param('id_user', null, PARAM_TEXT);
        if ($iduser == null) {
            $iduser = get_user_details(array('id_user_moodle' => $USER->id))->id;
        }

        // Profile menu
        echo $this->lipsoutput->display_profile_menu('profile') . '<br/>';

        // Recent activity
        echo $this->lipsoutput->display_h1(get_string('recent_activity', 'lips'));
        echo $this->lipsoutput->display_notifications(fetch_notifications_details('notification_user_id = ' . $iduser . ' AND (notification_from = ' . $iduser . ' OR notification_to = ' . $iduser . ')'));

        // Achievements
        echo '<br/>' . $this->lipsoutput->display_h1(get_string('achievements', 'lips'));
        echo $this->lipsoutput->display_achievements(fetch_achievements_details($iduser));
    }
}

/**
 * Profile ranks page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile_ranks extends page_view {

    /**
     * page_profile_ranks constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile_ranks content
     */
    function display_content() {
        global $USER;

        echo $this->lipsoutput->display_profile_menu('ranks') . '<br/>';

        echo $this->lipsoutput->display_h1(get_string('ranks', 'lips'));

        $userid = optional_param('id_user', null, PARAM_TEXT);
        if ($userid == null) {
            $userid = get_user_details(array('id_user_moodle' => $USER->id))->id;
        }

        echo $this->lipsoutput->display_ranks($userid);
    }
}

/**
 * Profile solved problems page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile_solved_problems extends page_view {

    /**
     * page_profile_solved_problems constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile_solved_problems content
     */
    function display_content() {
        echo $this->lipsoutput->display_profile_menu('solved_problems') . '<br/>';
        echo 'Solved problems';
    }
}

/**
 * Profile challenges page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaiïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile_challenges extends page_view {

    /**
     * page_profile_challenges constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile_challenges content
     */
    function display_content() {
        global $USER;

        require_once(dirname(__FILE__) . '/../tables/received_challenges_table.php');
        require_once(dirname(__FILE__) . '/../tables/sent_challenges_table.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_received_challenges_search_form.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_sent_challenges_search_form.php');

        echo $this->lipsoutput->display_profile_menu('challenges') . '<br/>';

        // User details
        $iduser = optional_param('id_user', null, PARAM_TEXT);
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));

        // Search form for received challenges
        if ($iduser == null) {
            $receivedSearchForm = new mod_lips_received_challenges_search_form(
                new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'challenges')), null, 'post', '', array('class' => 'search-form'));
        } else {
            $receivedSearchForm = new mod_lips_received_challenges_search_form(
                new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'challenges', 'id_user' => $iduser)), null, 'post', '', array('class' => 'search-form'));
        }

        // Search result
        $receivedsearch = new stdClass();
        if ($receivedSearchForm->is_submitted()) {
            $data = $receivedSearchForm->get_submitted_data();
            if (!empty($data->problemInputSearch)) {
                $receivedsearch->problem = $data->problemInputSearch;
            }
            if (!empty($data->authorInputSearch)) {
                $receivedsearch->author = $data->authorInputSearch;
            }
        }

        // Received challenges table
        echo $this->lipsoutput->display_h1(get_string('received_challenges', 'lips'));
        $receivedSearchForm->display();

        if ($iduser == null || $iduser == $userdetails->id) {
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
            $receivedchallengestable = new received_challenges_table($this->cm, $userdetails->id, true, $receivedsearch);
        } else {
            $receivedchallengestable = new received_challenges_table($this->cm, $iduser, false, $receivedsearch);
        }
        $receivedchallengestable->out(get_string('challenges_table', 'lips'), true);

        // Search form for sent challenges
        if ($iduser == null) {
            $sentSearchForm = new mod_lips_sent_challenges_search_form(
                new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'challenges')), null, 'post', '', array('class' => 'search-form'));
        } else {
            $sentSearchForm = new mod_lips_sent_challenges_search_form(
                new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'challenges', 'id_user' => $iduser)), null, 'post', '', array('class' => 'search-form'));
        }

        // Search result
        $sentsearch = new stdClass();
        if ($sentSearchForm->is_submitted()) {
            $data = $sentSearchForm->get_submitted_data();
            if (!empty($data->problemInputSearch)) {
                $sentsearch->problem = $data->problemInputSearch;
            }
            if (!empty($data->authorInputSearch)) {
                $sentsearch->author = $data->authorInputSearch;
            }
        }

        // Sent challenges table
        echo '<br/>' . $this->lipsoutput->display_h1(get_string('sent_challenges', 'lips'));
        $sentSearchForm->display();

        if ($iduser == null || $iduser == $userdetails->id) {
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
            $sentchallengestable = new sent_challenges_table($this->cm, $userdetails->id, true, $sentsearch);
        } else {
            $sentchallengestable = new sent_challenges_table($this->cm, $iduser, false, $sentsearch);
        }
        $sentchallengestable->out(get_string('challenges_table', 'lips'), true);
    }
}

/**
 * Profile followed users page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Valentin Got
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_profile_followed_users extends page_view {

    /**
     * page_profile_followed_users constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "profile");
    }

    /**
     * Display the page_profile_followed_users content
     */
    function display_content() {
        global $USER;

        require_once(dirname(__FILE__) . '/../tables/followed_users_table.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_search_form.php');

        echo $this->lipsoutput->display_profile_menu('followed_users') . '<br/>';
        echo $this->lipsoutput->display_h1(get_string('followed_users', 'lips'));

        // User details
        $iduser = optional_param('id_user', null, PARAM_TEXT);
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));

        // Search form
        if ($iduser == null) {
            $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'followed_users')), null, 'post', '', array('class' => 'search-form'));
        } else {
            $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'followed_users', 'id_user' => $iduser)), null, 'post', '', array('class' => 'search-form'));
        }
        $searchForm->display();

        // Search result
        $search = null;
        if ($searchForm->is_submitted()) {
            $data = $searchForm->get_submitted_data();
            if (!empty($data->inputSearch)) {
                $search = $data->inputSearch;
            }
        }

        // Followed users table
        if ($iduser == null || $iduser == $userdetails->id) {
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));

            $table = new followed_users_table($this->cm, $userdetails->id, true);
        } else {
            $table = new followed_users_table($this->cm, $iduser, false);
        }
        $table->out(get_string('followed_users_table', 'lips'), true);
    }
}