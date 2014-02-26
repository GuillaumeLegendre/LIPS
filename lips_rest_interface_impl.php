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

class lips_rest_interface_impl implements lips_rest_interface {

    public static function execute($source, $language) {

        global $CFG;

        $postdata = http_build_query(
            array(
                'code' => base64_encode($source),
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

        // Get url of the web service from config file.
        $config = parse_ini_file($CFG->dirroot . "/mod/lips/config.ini", true);
        $serviceurl = $config['web_services']['service_compil_url'];

        $json = file_get_contents($serviceurl, false, $context);
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
        global $CFG;

        $languages = array();

        // Get url of the web service from config file.
        $config = parse_ini_file($CFG->dirroot . "/mod/lips/config.ini", true);
        $serviceurl = $config['web_services']['service_languages_url'];

        $json = file_get_contents($serviceurl);
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