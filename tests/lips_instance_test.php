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

require_once('/var/www/moodle/lib/phpunit/classes/advanced_testcase.php');
require_once(dirname(__FILE__) . '/../lib.php');

/**
 * Tests class for mod_lips.
 *
 * @package    mod_lips
 * @category   tests
 * @copyright  2014 Anaïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lips_testcase extends advanced_testcase {

    public function test_lips_add_instance() {
        global $DB;
        $this->resetAfterTest();

        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $this->assertFalse($DB->record_exists('lips', array('course' => $course->id)));

        // Create the first lips instance.
        $params = (object) array ('course' => $course->id, 'name' => 'lips');
        $id = lips_add_instance($params);
        
        $records = $DB->get_records('lips', array('course' => $course->id), 'id');
        $this->assertEquals(1, count($records));
        $this->assertTrue(array_key_exists($id, $records));

        // Create the second lips instance.
        $params = (object) array ('course' => $course->id, 'name' => 'another lips');
        $id = lips_add_instance($params);

        $records = $DB->get_records('lips', array('course' => $course->id), 'id');
        $this->assertEquals(2, count($records));
        $this->assertEquals('another lips', $records[$id]->name);
    }

    public function test_lips_update_instance() {
        global $DB;
        $this->resetAfterTest();

        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();

        // Create lips instance.
        $params = (object) array ('course' => $course->id, 'name' => 'lips');
        $id = lips_add_instance($params);

        // Update the instance.
        $params = (object) array ('instance' => $id, 'course' => $course->id, 'name' => 'new lips');
        lips_update_instance($params);

        $records = $DB->get_records('lips', array('course' => $course->id), 'id');
        $this->assertEquals(1, count($records));
        $this->assertEquals('new lips', $records[$id]->name);
    }

    public function test_lips_delete_instance() {
        global $DB;
        $this->resetAfterTest();

        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();

        // Create lips instance.
        $params = (object) array ('course' => $course->id, 'name' => 'lips');
        $id = lips_add_instance($params);

        // Delete the lips instance.
        lips_delete_instance($id);

        $this->assertFalse($DB->record_exists('lips', array('course' => $course->id)));
    }
}
