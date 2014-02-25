<?php

require_once(dirname(__FILE__) . '/page_view.php');

/**
 * Confirmation cancel challenges page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaiïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_cancel_challenge extends page_view {
    private $idChallenge;
    private $originv;
    private $originaction;

    function  __construct($cm, $id, $originv, $originaction) {
        parent::__construct($cm, "cancelChallenge");

        $this->idChallenge = $id;
        $this->originv = $originv;
        $this->originaction = $originaction;
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        $message = $this->lipsoutput->display_h2(get_string('administration_cancel_challenge_confirmation', 'lips'));

        $continueurl = new moodle_url('action.php', array('id' => $this->cm->id, 'action' => 'cancel_challenge', 'originV' => $this->originv, 'originAction' => $this->originaction, 'challengeId' => $this->idChallenge));
        $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => "profile", 'action' => "challenges"));

        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}