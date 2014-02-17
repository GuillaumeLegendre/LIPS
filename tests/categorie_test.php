<?php
require_once('/var/www/moodle/lib/phpunit/classes/advanced_testcase.php');
require_once(dirname(__FILE__) . '/../locallib.php');

class mod_categorie_testcase extends advanced_testcase {

 	/*
		Check that insert_category() inserts the specified category in db.
		- tested method in locallib.php
    */
 	public function test_insert_category() {
 		global $DB;	
 		$this->resetAfterTest(true);

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
 		$this->resetAfterTest(true);
		
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
		$this->resetAfterTest(true);

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
 		$this->resetAfterTest(true);

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
		$this->resetAfterTest(true);

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
		$this->resetAfterTest(true);

 		$this->assertEquals($DB->get_records('lips_category', array()), fetch_all_categories(1));
 	}
}