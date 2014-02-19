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

    public static function execute($source) {
        $languages = array();
        $data = new stdClass();
        $data->code = $source;
        $data->language = "c";
        $json_data = json_encode($data);
        print_object($json_data);

        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => "Content-type: application/json;charset=utf-8\r\n" .
                    "Connection: close\r\n" .
                    "Content-length: " . strlen($json_data) . "\r\n",
                'content' => $json_data,
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents("http://localhost:4567/compilation", false, $context);
        print_object($result);

        return $languages;

    }

    public static function get_list_languages() {
        $languages = array();
        $json = file_get_contents("http://localhost:4567/available_languages");
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