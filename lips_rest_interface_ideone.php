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

require_once(dirname(__FILE__) . '/lips_rest_interface.php');

class lips_rest_interface_ideone implements lips_rest_interface {

    public static function execute($source, $language) {
        // creating soap client
        $client = new SoapClient("http://ideone.com/api/1/service.wsdl");
        // calling test function
        $testArray = $client->createSubmission("mohlen", "lips", $source, 29, "", true, true);
        $res = $client->getSubmissionDetails("mohlen", "lips", $testArray['link'], true, true, true, true, true);
        // printing returned values
        while ($res['status'] != 0) {
            sleep(3);
            $res = $client->getSubmissionDetails("mohlen", "lips", $testArray['link'], true, true, true, true, true);
        }
        $resarray = array();
        if ($res['result'] == 15) {
            $resarray['result'] = 1;
        } else {
            $resarray['result'] = 0;
        }
        $resarray['error'] = $res['stderr'];
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
            $languages[$languageid] = $languagename;
        }
        return $languages;
    }
}