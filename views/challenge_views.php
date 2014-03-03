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
 * Confirmation cancel challenges page
 *
 * @package    mod_lips
 * @copyright  2014 LIPS
 * @author     Anaiïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_cancel_challenge extends page_view {
    private $idchallenge;
    private $originv;
    private $originaction;

    function  __construct($cm, $id, $originv, $originaction) {
        parent::__construct($cm, "cancelChallenge");

        $this->idchallenge = $id;
        $this->originv = $originv;
        $this->originaction = $originaction;
    }

    /**
     * Display the message of confirmation.
     */
    function display_content() {
        $message = $this->lipsoutput->display_h2(get_string('administration_cancel_challenge_confirmation', 'lips'));

        $continueurl = new moodle_url('action.php',
            array('id' => $this->cm->id,
                'action' => 'cancel_challenge',
                'originV' => $this->originv,
                'originAction' => $this->originaction,
                'challengeId' => $this->idchallenge));
        $cancelurl = new moodle_url('view.php', array('id' => $this->cm->id, 'view' => "profile", 'action' => "challenges"));
        echo $this->lipsoutput->confirm($message, $continueurl, $cancelurl);
    }
}