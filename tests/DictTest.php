<?php

namespace Eightfold\Shoop\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\Helpers\Type;
use Eightfold\Shoop\ESDictionary;
use Eightfold\Shoop\ESArray;

use Eightfold\Shoop\Tests\TestObject;

class DictTest extends TestCase
{
    public function testCanTypeJuggle()
    {
        $expected = [1, 2];
        $dict = ["one" => 1, "two" => 2];
        $result = ESDictionary::fold($dict)->array();
        $this->assertEquals($expected, $result->unfold());

        $result = ESDictionary::fold($dict)->dictionary();
        $this->assertEquals($dict, $result->unfold());
    }

    public function testPHPSingleMethodInterfaces()
    {
        $expected = "Array([zero] => 0, [one] => 1)";
        $result = (string) ESDictionary::fold(["zero" => 0, "one" => 1]);
        $this->assertEquals($expected, $result);
    }

    public function testCanManipulate()
    {
        $dict = ["zero" => 0, "one" => 1];
        $result = ESDictionary::fold($dict)->toggle();
        $this->assertEquals(["one" => 1, "zero" => 0], $result->unfold());

        $result = $result->sort();
        $this->assertEquals([0, 1], $result->unfold());
    }

    public function testSearch()
    {
        $base = ["one" => 1, "two" => 2];
        $shoopDict = Shoop::dictionary($base);
        $result = $shoopDict->contains(1);
        $this->assertTrue($result->unfold());

        $result = $shoopDict->startsWith([1, 2]);
        $this->assertTrue($result->unfold());
    }

    public function testDictionaryCanMath()
    {
        $expected = ["zero" => 0, "one" => 1];
        $result = ESDictionary::fold(["zero" => 0])->plusUnfolded("one", 1);
        $this->assertEquals($expected, $result);

        $result = ESDictionary::fold($expected)->minusUnfolded("zero");
        $this->assertEquals(["one" => 1], $result);
        $base = ["one" => 1, "two" => 2];
        $expected = [
            $base,
            $base,
            $base
        ];
        $result = ESDictionary::fold($base)->multiplyUnfolded(3);
        $this->assertEquals($expected, $result);
    }









    public function testCanInitializeDict()
    {
        $result = Shoop::dictionary(["key" => "value"]);
        $this->assertNotNull($result);

        $this->assertEquals("value", $result["key"]);
    }

    public function testCanIterateDictionary()
    {
        $dict = Shoop::dictionary(["key" => "value", "key2" => "value2"]);
        $this->assertEquals(["key" => "value", "key2" => "value2"], $dict->unfold());
        $count = 1;
        foreach($dict as $key => $value) {
            if ($key === "key") {
                $this->assertEquals("value", $value);

            } elseif ($key === "key2") {
                $this->assertEquals("value2", $value);

            }
            $count++;
        }
        $this->assertTrue($count > 1);
    }

    public function testCanCheckForKey()
    {
        $result = Shoop::dictionary(["key" => "value"])
            ->hasKey("key");
        $this->assertTrue($result->unfold());
    }

    public function testCanGetValueForKey()
    {
        $assoc = [
            "one" => 1,
            "two" => [1, 2],
            "three" => (object) [
                "one" => 1,
                "two" => 2
            ],
            "four" => Shoop::array([1, 2]),
            "five" => (new TestObject)
        ];

        $dict = Shoop::dictionary($assoc);
        $this->assertEquals(
            1,
            $dict->valueForKeyUnfolded("one")
        );

        $this->assertTrue(
            is_array(
                $dict->valueForKeyUnfolded("two")
            )
        );

        $this->assertTrue(
            is_a(
                $dict->valueForKeyUnfolded("three"),
                \stdClass::class
            )
        );

        $this->assertTrue(Type::isShooped(
            // TODO: Possibly remove "Unfolded" suffix shorthand
            $dict->valueForKey("four")
        ));

        $this->assertTrue(
            is_a(
                $dict->valueForKeyUnfolded("five"),
                TestObject::class
            )
        );
    }



}
