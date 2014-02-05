<?php
require_once("../locallib.php");

/* Test locallib.php file */

class CategorieTest extends PHPUnit_Framework_TestCase
{

    /* Check category creation */

    /* Check category delete */

    /* Check category update */

    /* Check category fields */

    public function test_get_category_details()
    {

        $details = get_category_details($this->id);
        $details->category_name;
        $details->category_documentation;
        $details->category_documentation_type;

    }
    
  /*  public function tester()
    {
        $pile = array();
        $this->assertEquals(0, count($pile));

        array_push($pile, 'foo');
        $this->assertEquals('foo', $pile[count($pile)-1]);
        $this->assertEquals(1, count($pile));

        $this->assertEquals('foo', array_pop($pile));
        $this->assertEquals(0, count($pile));
    } */
}
?>
