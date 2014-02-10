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
 * This interface represents the code used to dialog with web services.
 * The are two web services used by the plugin.
 * The first web service permits to obtain the list of languages available.
 * The second permits to execute source code and get the result of execution.
 *
 */
interface lips_rest_interface {
    public static function execute($source);
    public static function get_list_languages();
}