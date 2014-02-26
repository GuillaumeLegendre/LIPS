<?php

require_once(dirname(__FILE__) . '/page_view.php');

/**
 * Index page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Mickaël Ohlen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_index extends page_view {

    /**
     * page_index constructor
     *
     * @param object $cm Moodle context
     */
    function  __construct($cm) {
        parent::__construct($cm, "index");
    }

    /**
     * Display the page_index content
     */
    function display_content() {
        global $USER;

        // User details
        $userdetails = get_user_details(array('id_user_moodle' => $USER->id));

        // Current challenges
        $currentchallengedetails = fetch_challenges(array('challenge_to' => $userdetails->id, 'challenge_state' => 'ACCEPTED'));
        echo $this->lipsoutput->display_h1(get_string('current_challenges', 'lips'));
        if (count($currentchallengedetails) > 0) {
            echo $this->lipsoutput->display_current_challenges($currentchallengedetails);
        } else {
            echo $this->lipsoutput->display_p(get_string('no_challenges', 'lips'));
        }

        // Received challenges
        $receivedchallengedetails = fetch_challenges(array('challenge_to' => $userdetails->id, 'challenge_state' => 'WAITING'));
        echo $this->lipsoutput->display_h1(get_string('received_challenges', 'lips'), array("style" => "margin-top: 15px"));
        if (count($receivedchallengedetails) > 0) {
            echo $this->lipsoutput->display_challenges($receivedchallengedetails);
        } else {
            echo $this->lipsoutput->display_p(get_string('no_challenges', 'lips'));
        }

        $page = optional_param('page', 1, PARAM_INT);
        // Notifications
        $notificationsdetails = fetch_notifications_details('notification_user_id = ' . $userdetails->id . ' AND notification_from <> ' . $userdetails->id . ' AND notification_to <> ' . $userdetails->id);
        echo $this->lipsoutput->display_h1(get_string('notifications', 'lips'));
        if (count($notificationsdetails) > 0) {
            echo $this->lipsoutput->display_notifications($notificationsdetails);
            if (count(fetch_notifications_details('notification_user_id = ' . $userdetails->id . ' AND notification_from <> ' . $userdetails->id . ' AND notification_to <> ' . $userdetails->id, $page + 1 * 15)) > $page * 15) {
                echo "<br/><center>" . $this->lipsoutput->render(new action_link(new moodle_url('view.php', array(
                            'id' => $this->cm->id,
                            'view' => $this->view,
                            'page' => $page * 15,
                        )),
                        get_string('display_more_results', 'lips'), null, array("class" => "lips-button"))) . "</center>";
            }


        } else {
            echo $this->lipsoutput->display_p(get_string('no_notifications', 'lips'));
        }
    }
}
