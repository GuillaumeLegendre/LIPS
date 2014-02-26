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

require_once('/var/www/moodle/lib/phpunit/classes/advanced_testcase.php');
require_once(dirname(__FILE__) . '/../lib.php');
require_once(dirname(__FILE__) . '/../locallib.php');

/**
 * Categories tests class for mod_lips.
 *
 * @package    mod_lips
 * @category   tests
 * @copyright  2014 Anaïs Picoreau
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_categories_testcase extends advanced_testcase {

	private function init_env() {
		global $DB, $USER;

		$this->resetAfterTest(true);
 		$this->setAdminUser();

 		$course = $this->getDataGenerator()->create_course();
        $id = lips_add_instance((object) array ('course' => $course->id, 'name' => 'lips'));

        $mid = $DB->insert_record('modules', (object) array ('name' => 'lips'));
        $params = (object) array ('course' => $course->id, 'module' => $mid, 'instance' => $id, 'section' => '1');
        $cmid = $DB->insert_record('course_modules', $params);

		$_POST['id'] = $cmid;

		insert_user($USER->id, "coursecreator", 1, 0);
	}

 	/*
		Check that insert_category() inserts the specified category in db.
		- tested method in locallib.php
    */
 	public function test_insert_category() {
 		global $DB;
 		
 		$this->init_env();

 		$id_language = 1;
 		$category_name = "test_unitaire";
 		$category_documentation = "texte";
 		$category_documentation_type = "TEXT";

 		insert_category($id_language, $category_name, $category_documentation, $category_documentation_type);

 		$category = $DB->get_record('lips_category', array('category_name' => $category_name));
 		$this->assertEquals($category->id_language, $id_language);
 		$this->assertEquals($category->category_name, $category_name);
 		$this->assertEquals($category->category_documentation, $category_documentation);
 		$this->assertEquals($category->category_documentation_type, $category_documentation_type);
 	}

	/*
		Check that delete_category() deletes the specified category from db.
		- tested method in locallib.php
    */
 	public function test_delete_category() {
 		global $DB;

 		$this->init_env();
		
 		$id_language = 1;
 		$category_name = "test_unitaire";
 		$category_documentation = "texte";
 		$category_documentation_type = "TEXT";

 		insert_category($id_language, $category_name, $category_documentation, $category_documentation_type);
		$category = $DB->get_record('lips_category', array('category_name' => $category_name));

		delete_category($category->id);
        $this->assertEmpty($DB->get_record('lips_category', array('category_name' => $category_name)));
    }

    /*
		Check that update_category() updates name, documentation and documentation type in db.
		- tested method in locallib.php
    */
 	public function test_update_category() {
		global $DB;
 		
 		$this->init_env();

		$id_language = 1;
 		$category_name = "test_unitaire";
 		$category_documentation = "texte";
 		$category_documentation_type = "TEXT";

 		insert_category($id_language, $category_name, $category_documentation, $category_documentation_type);
		$category = $DB->get_record('lips_category', array('category_name' => $category_name));

		$id_language = 2;
 		$category_name = "test_unitaire2";
 		$category_documentation = "lien";
 		$category_documentation_type = "LINK";

		update_category($category->id, $category_name, $category_documentation, $category_documentation_type);

		$category = $DB->get_record('lips_category', array('id' => $category->id));

		$this->assertEquals($category->category_name, $category_name);
 		$this->assertEquals($category->category_documentation, $category_documentation);
 		$this->assertEquals($category->category_documentation_type, $category_documentation_type);
 	}

    /*
		Check that category_exists() return true if category exists, else in other cases.
		- tested method in locallib.php
    */
 	public function test_category_exists() {
 		
 		$this->init_env();

 		$id_language = 1;
 		$category_name = "test_unitaire";
 		$category_documentation = "texte";
 		$category_documentation_type = "TEXT";

 		$this->assertFalse(category_exists(array('category_name' => $category_name)));
 		
 		insert_category($id_language, $category_name, $category_documentation, $category_documentation_type);
 		$this->assertTrue(category_exists(array('category_name' => $category_name)));
 	}

 	 /*
		Check that get_category_details() returns category data : id_language, category_name,
		category_documentation, category_documentation_type.
		- tested method in locallib.php
    */
 	public function test_get_category_details() {
 		global $DB;
 		
 		$this->init_env();

		$id_language = 1;
 		$category_name = "test_unitaire";
 		$category_documentation = "texte";
 		$category_documentation_type = "TEXT";

 		insert_category($id_language, $category_name, $category_documentation, $category_documentation_type);
 		$category = $DB->get_record('lips_category', array('category_name' => $category_name));
	
 		$category_details = get_category_details($category->id);
 		$this->assertEquals($category_details->id_language, $id_language);
 		$this->assertEquals($category_details->category_name, $category_name);
 		$this->assertEquals($category_details->category_documentation, $category_documentation);
 		$this->assertEquals($category_details->category_documentation_type, $category_documentation_type);
 	}

 	 /*
		Check that fetch_all_categories() returns all categories.
		- tested method in locallib.php
    */
 	public function test_fetch_all_categories() {
		global $DB;
 		
 		$this->init_env();

 		$this->assertEquals($DB->get_records('lips_category', array()), fetch_all_categories(1));
 	}
}