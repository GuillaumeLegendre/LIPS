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

class lips_rest_interface_impl implements lips_rest_interface {

    public static function execute($source, $language) {
        $postdata = http_build_query(
            array(
                'code' => $source,
                'language' => $language
            )
        );

        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $json = file_get_contents("http://localhost:4567/execute", false, $context);
        if (!$json) {
            return false;
        }
        $data = json_decode($json);
        $resarray = array();
        if (empty($data->stderr)) {
            $resarray['result'] = 1;
        } else {
            $resarray['result'] = 0;
        }
        $resarray['error'] = $data->stderr;
        $resarray['output'] = $data->stdout;
        return $resarray;
    }

    public static function get_list_languages() {
        $languages = array();
        $json = file_get_contents("http://localhost:4567/languages");
        if (!$json) {
            return false;
        }
        $data = json_decode($json);
        foreach ($data->languages as $language) {
            $languages[$language] = $language;
        }
        return $languages;
    }
}