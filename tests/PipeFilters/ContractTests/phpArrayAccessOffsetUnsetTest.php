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
 * The `offsetUnset()` method is part of the `ArrayAccess` interface and removes the specified member.
 *
 * Most implementations are based on the array representation of the `Shoop type`.
 *
 * @return bool
 */
class OffsetUnsetTest extends TestCase
{
    public function ESArray()
    {
        $actual = ESArray::fold([false, true]);
        $actual->offsetUnset(0);
        $this->assertTrue($actual->getUnfolded(1));
    }

    /**
     * No changes made.
     */
    public function ESBoolean()
    {
        $actual = ESBoolean::fold(true);
        $actual->offsetUnset(0);
        $this->assertTrue($actual->unfold());
    }

    public function ESDictionary()
    {
        $actual = ESDictionary::fold(["member" => false]);
        $actual->offsetUnset("member");
        $this->assertFalse($actual->offsetExists("member"));
    }

    /**
     * No changes made.
     */
    public function ESInteger()
    {
        $actual = ESInteger::fold(10);
        $actual->offsetUnset(8);
        $this->assertEquals(10, $actual->unfold());
    }

    public function ESJson()
    {
        $expected = '{}';
        $actual = ESJson::fold('{"test":true}');
        $actual->offsetUnset("test");
        $this->assertEquals($expected, $actual->unfold());
    }

    public function ESTuple()
    {
        $base = new \stdClass();
        $base->test = false;

        $actual = ESTuple::fold($base);
        $actual->offsetUnset("test");
        $this->assertFalse($actual->offsetExists("test"));
    }

    public function ESString()
    {
        $expected = "cop";
        $actual = ESString::fold("comp");
        $actual->offsetUnset(2);
        $this->assertEquals($expected, $actual);
    }
}