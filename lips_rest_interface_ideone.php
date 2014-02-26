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

require_once(dirname(__FILE__) . '/lips_rest_interface.php');

class lips_rest_interface_ideone implements lips_rest_interface {

    public static function execute($source, $language) {
        // creating soap client
        $languageid = "";
        $client = new SoapClient("http://ideone.com/api/1/service.wsdl");
        $languagessupported = $client->getLanguages("mohlen", "lips");
        foreach ($languagessupported['languages'] as $langid => $langname) {
            if ($langname == $language) {
                $languageid = $langid;
            }
        }

        // calling test function
        $testArray = $client->createSubmission("mohlen", "lips", $source, $languageid, "", true, true);
        $res = $client->getSubmissionDetails("mohlen", "lips", $testArray['link'], true, true, true, true, true);
        // printing returned values
        while ($res['status'] != 0) {
            sleep(3);
            $res = $client->getSubmissionDetails("mohlen", "lips", $testArray['link'], true, true, true, true, true);
        }
        $resarray = array();
        $resarray['error'] = $res['stderr'];
        if ($res['result'] == 15 || $res['result'] == 12) {
            $resarray['result'] = 1;
        } else if ($res['result'] == 11) {
            $resarray['result'] = 0;
            $resarray['error'] = $res['cmpinfo'];
        } else {
            $resarray['result'] = 0;
        }
        $resarray['output'] = $res['output'];
        return $resarray;
    }

    public static function get_list_languages() {
        $languages = array();
        // creating soap client
        $client = new SoapClient("http://ideone.com/api/1/service.wsdl");
        // calling test function
        $languagessupported = $client->getLanguages("mohlen", "lips");
        foreach ($languagessupported['languages'] as $languageid => $languagename) {
            $languages[$languagename] = $languagename;
        }
        return $languages;
    }
}