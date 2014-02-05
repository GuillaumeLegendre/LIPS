<?php
class CategorieTest extends PHPUnit_Framework_TestCase
{
    public function tester()
    {
        $pile = array();
        $this->assertEquals(0, count($pile));

        array_push($pile, 'foo');
        $this->assertEquals('foo', $pile[count($pile)-1]);
        $this->assertEquals(1, count($pile));

        $this->assertEquals('foo', array_pop($pile));
        $this->assertEquals(0, count($pile));
    }
}
?>
