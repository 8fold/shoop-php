<?php

namespace Eightfold\Shoop\Tests\PipeFilters;

use \stdClass;

use Eightfold\Shoop\Tests\TestCase;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\PipeFilters\AsBool;
use Eightfold\Shoop\PipeFilters\AsBool\FromArray;
use Eightfold\Shoop\PipeFilters\AsBool\FromInt;
use Eightfold\Shoop\PipeFilters\AsBool\FromJson;
use Eightfold\Shoop\PipeFilters\AsBool\FromObject;
use Eightfold\Shoop\PipeFilters\AsBool\FromString;

class AsBoolTest extends TestCase
{
    public function test_from_array()
    {
        $payload = [0, 1, 2, 3];

        $this->start = hrtime(true);
        $expected = true;
        $actual   = Shoop::pipe($payload, AsBool::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual, 1.7);

        $this->start = hrtime(true);
        $expected = true;
        $actual   = Shoop::pipe($payload, FromArray::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_bool()
    {
        $payload = true;
        $expected = true;
        $actual = Shoop::pipe($payload, AsBool::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_dictionary()
    {
        $payload = ["true" => false];

        $expected = true;
        $actual = Shoop::pipe($payload, AsBool::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $actual = Shoop::pipe($payload, FromArray::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_int()
    {
        $payload = 0;
        $expected = false;
        $actual = Shoop::pipe($payload, AsBool::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $payload = 100;
        $expected = true;
        $actual = Shoop::pipe($payload, FromInt::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $payload = -3;
        $expected = true;
        $actual = Shoop::pipe($payload, FromInt::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_json()
    {
        // TODO: Should an empty JSON object return false
        //      how close should empty and bool be??
        $this->start = hrtime(true);
        $payload = '{}';
        $expected = true;//false
        $actual = Shoop::pipe($payload, AsBool::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual, 0.85);

        $this->start = hrtime(true);
        $payload = '{"member":false}';
        $expected = true;
        $actual = Shoop::pipe($payload, FromJson::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_object()
    {
        // TODO: Should an empty object return false
        //      how close should empty and bool be??
        //      we did say that an empty array is a
        //      ditionary because it could become one
        $payload = new stdClass;
        $expected = true;// false
        $actual = Shoop::pipe($payload, AsBool::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $payload->member = false;
        $expected = true;
        $actual = Shoop::pipe($payload, FromObject::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_string()
    {
        $payload = "";
        $expected = false;
        $actual = Shoop::pipe($payload, AsBool::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        // TODO: Should we convert the strings of "true" and "false"
        //      directly?? Basically, as long as the string is not
        //      empty, it is considered true by PHP type juggling.
        $payload = "false";
        $expected = true; // false
        $actual = Shoop::pipe($payload, FromString::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }
}