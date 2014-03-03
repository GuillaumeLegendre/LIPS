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

/**
 * This interface represents the code used to dialog with web services.
 * The are two web services used by the plugin.
 * The first web service allows the plugin to get the list of available languages.
 * The second permits to execute source code and get the result of the execution.
 *
 */
interface lips_webservices_interface {
    /**
     * Returns the result of the execution of a source code.
     *
     * @param $source source code
     * @param $language name of the language
     * @return array An array containing the result of the execution.
     *      Ex : $array['result'] => 0 if an error occured. 1 otherwise.
     *           $array['error'] => The error message if present.
     *           $array['output'] => The stdout.
     */
    public static function execute($source, $language);

    /**
     * Returns an array containing all available languages
     * on the web service.
     * @return array An associative array.
     *      Ex : $array['Java'] => "Java"
     */
    public static function get_list_languages();
}