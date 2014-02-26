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
        $page = optional_param('page', 1, PARAM_INT);
        if ($iduser == null) {
            $iduser = get_user_details(array('id_user_moodle' => $USER->id))->id;
        }

        // Profile menu
        echo $this->lipsoutput->display_profile_menu('profile') . '<br/>';

        // Recent activity
        echo $this->lipsoutput->display_h1(get_string('recent_activity', 'lips'));
        echo $this->lipsoutput->display_notifications(fetch_notifications_details('notification_user_id = ' . $iduser . ' AND (notification_from = ' . $iduser . ' OR notification_to = ' . $iduser . ')', $page * 15));

        if (count(fetch_notifications_details('notification_user_id = ' . $iduser . ' AND (notification_from = ' . $iduser . ' OR notification_to = ' . $iduser . ')', $page + 1 * 15)) > $page * 15) {
            echo "<br/><center>" . $this->lipsoutput->render(new action_link(new moodle_url('view.php', array(
                        'id' => $this->cm->id,
                        'view' => $this->view,
                        'page' => $page * 15,
                        'id_user' => $iduser
                    )),
                    get_string('display_more_results', 'lips'), null, array("class" => "lips-button"))). "</center>";
        }


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
        global $USER;
        require_once(dirname(__FILE__) . '/../tables/solved_problems_table.php');
        require_once(dirname(__FILE__) . '/../form/mod_lips_search_form.php');

        echo $this->lipsoutput->display_profile_menu('solved_problems') . '<br/>';
        echo $this->lipsoutput->display_h1(get_string('solved_problems', 'lips'));

        // User details
        $userid = optional_param('id_user', null, PARAM_TEXT);
        if ($userid == null) {
            $curentid = get_user_details(array('id_user_moodle' => $USER->id))->id;
            $userdetails = get_user_details(array('id_user_moodle' => $USER->id));
        } else {
            $userdetails = get_user_details(array('id' => $userid));
        }

        // Search form
        $array = array(
            "placeholder" => get_string('problem', 'lips'),
            "class" => "solved_problems_ac"
        );
        echo '<input type="hidden" id="hiddenUserID" value="' . $userdetails->id_user_moodle . '"/>';
        if ($userid == null) {
            $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'solved_problems')), $array, 'post', '', array('class' => 'search-form'));
        } else {
            $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'solved_problems', 'id_user' => $userid)), $array, 'post', '', array('class' => 'search-form'));
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
        if ($userid == null || $userid == $curentid) {
            $table = new solved_problems_table($this->cm, $search, $userdetails->id_user_moodle, true);
        } else {
            $table = new solved_problems_table($this->cm, $search, $userdetails->id_user_moodle, false);
        }
        $table->out(get_string('solved_problems_table', 'lips'), true);
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
        echo '<input type="hidden" id="hiddenUserID" value="' . $userdetails->id . '"/>';

        // Search form for received challenges
        if ($iduser == null) {
            $receivedSearchForm = new mod_lips_received_challenges_search_form(new moodle_url('view.php', array(
                'id' => $this->cm->id, 
                'view' => $this->view, 
                'action' => 'challenges')
            ), null, 'post', '', array('class' => 'search-form'));
        } else {
            $receivedSearchForm = new mod_lips_received_challenges_search_form(new moodle_url('view.php', array(
                'id' => $this->cm->id, 
                'view' => $this->view, 
                'action' => 'challenges', 
                'id_user' => $iduser)
            ), null, 'post', '', array('class' => 'search-form'));
        }

        // Search result
        $receivedsearch = new stdClass();
        if ($receivedSearchForm->is_submitted()) {
            $data = $receivedSearchForm->get_submitted_data();
            if (!empty($data->problemInputSearchReceived)) {
                $receivedsearch->problem = $data->problemInputSearchReceived;
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
            $sentSearchForm = new mod_lips_sent_challenges_search_form(new moodle_url('view.php', array(
                'id' => $this->cm->id, 
                'view' => $this->view, 
                'action' => 'challenges')
            ), null, 'post', '', array('class' => 'search-form'));
        } else {
            $sentSearchForm = new mod_lips_sent_challenges_search_form(new moodle_url('view.php', array(
                'id' => $this->cm->id, 
                'view' => $this->view, 
                'action' => 'challenges', 
                'id_user' => $iduser)
            ), null, 'post', '', array('class' => 'search-form'));
        }

        // Search result
        $sentsearch = new stdClass();
        if ($sentSearchForm->is_submitted()) {
            $data = $sentSearchForm->get_submitted_data();
            if (!empty($data->problemInputSearchSent)) {
                $sentsearch->problem = $data->problemInputSearchSent;
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
        $array = array(
            "placeholder" => get_string('user', 'lips'),
            "class" => "followed_users_ac"
        );

        echo '<input type="hidden" id="hiddenUserID" value="' . $userdetails->id . '"/>';
        if ($iduser == null) {
            $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'followed_users')), $array, 'post', '', array('class' => 'search-form'));
        } else {
            $searchForm = new mod_lips_search_form(new moodle_url('view.php', array('id' => $this->cm->id, 'view' => $this->view, 'action' => 'followed_users', 'id_user' => $iduser)), $array, 'post', '', array('class' => 'search-form'));
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

            $table = new followed_users_table($this->cm, $userdetails->id, true, $search);
        } else {
            $table = new followed_users_table($this->cm, $iduser, false, $search);
        }
        $table->out(get_string('followed_users_table', 'lips'), true);
    }
}