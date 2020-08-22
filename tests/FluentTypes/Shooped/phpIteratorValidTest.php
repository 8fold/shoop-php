<?php

namespace Eightfold\Shoop\Tests\Shooped;

use PHPUnit\Framework\TestCase;



use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\FluentTypes\{
    ESArray,
    ESBoolean,
    ESDictionary,
    ESInteger,
    ESJson,
    ESTuple,
    ESString
};

/**
 * The `valid()` method from the `Iterator` interface returns whether the current pointer position is valid.
 *
 * @return bool
 */
class InterfaceIteratorValidTest extends TestCase
{
    public function testESArray()
    {
        $expected = 0;
        $array = ESArray::fold(["hello", "goodbye"]);
        $this->assertTrue($array->valid());
        $array->next();
        $this->assertTrue($array->valid());
        $array->next();
        $this->assertFalse($array->valid());
    }

    public function testESBoolean()
    {
        $actual = ESBoolean::fold(true);
        $this->assertTrue($actual->valid());
    }

    public function testESDictionary()
    {
        $expected = "two";
        $actual = ESDictionary::fold(["one" => "hello", "two" => "goodbye"]);
        $actual->next();
        $this->assertEquals($expected, $actual->key());
    }

    public function testESInteger()
    {
        $actual = ESInteger::fold(10);
        $this->assertEquals(10, $actual->unfold());
    }

    public function testESJson()
    {
        $expected = "two";
        $actual = ESJson::fold('{"one":"hello", "two":"goodbye"}');
        $actual->next();
        $this->assertEquals($expected, $actual->key());
    }

    public function testESTuple()
    {
        $base = new \stdClass();
        $base->one = "hello";
        $base->two = "goodbye";

        $actual = ESTuple::fold($base);
        $this->assertEquals("one", $actual->key());
    }

    public function testESString()
    {
        $expected = 0;
        $actual = ESString::fold("comp");
        $this->assertEquals($expected, $actual->key());
    }
}
