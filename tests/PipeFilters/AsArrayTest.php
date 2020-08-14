<?php

namespace Eightfold\Shoop\Tests\PipeFilters;

use \stdClass;

use Eightfold\Shoop\Tests\TestCase;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\PipeFilters\AsArray;
use Eightfold\Shoop\PipeFilters\AsArray\FromBool;
use Eightfold\Shoop\PipeFilters\AsArray\FromInt;
use Eightfold\Shoop\PipeFilters\AsArray\FromJson;
use Eightfold\Shoop\PipeFilters\AsArray\FromObject;
use Eightfold\Shoop\PipeFilters\AsArray\FromString;

class AsArrayTest extends TestCase
{
    public function test_from_array()
    {
        $payload = [0, 1, 2, 3];

        $expected = $payload;
        $actual   = Shoop::pipe($payload, AsArray::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual, 1.3);
        # pipe:   1.11 - 1.12 - 1.01 - 1.14
        # filter: 0.99 - 1.00 - 1.09 - 1.1
        # php:    0.93 - 0.96 - 0.86 - 0.82

        $this->start = hrtime(true);
        $payload = [0 => 1, 1 => 0, 2 => 3, 3 => 2];
        $expected = [1, 0, 3, 2];
        $actual   = Shoop::pipe($payload, AsArray::applyWith(false))->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_bool()
    {
        $payload = true;

        $expected = [true];
        $actual = Shoop::pipe($payload, AsArray::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $expected = [true];
        $actual = Shoop::pipe($payload, FromBool::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_dictionary()
    {
        $payload = ["a" => 0, "b" => 1, "c" => 2, "d" => 3];

        $expected = [0, 1, 2, 3];
        $actual   = Shoop::pipe($payload, AsArray::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual, 1.3);

        $this->start = hrtime(true);
        $expected = [0, 1, 2, 3];
        $actual   = Shoop::pipe($payload, AsArray::applyWith(false))->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_int()
    {
        $payload = 3;

        $this->start = hrtime(true);
        $expected = [0, 1, 2, 3];
        $actual = Shoop::pipe($payload, AsArray::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $this->start = hrtime(true);
        $expected = [2, 3];
        $actual = Shoop::pipe($payload, AsArray::applyWith(2))->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $expected = [2, 3];
        $actual = Shoop::pipe($payload, FromInt::applyWith(2))->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_json()
    {
        $payload = '{"member":true}';

        $this->start = hrtime(true);
        $expected = [true];
        $actual = Shoop::pipe($payload, AsArray::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual, 1.8);
        # pipe:   1.60 - 1.64 - 1.55 - 1.52
        # filter: 1.72 - 1.54 - 1.54 - 1.50

        $this->start = hrtime(true);
        $expected = [true];
        $actual = Shoop::pipe($payload, FromJson::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_object()
    {
        $payload = new stdClass;
        $payload->member = "8fold";

        $expected = ["8fold"];
        $actual = Shoop::pipe($payload, AsArray::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $expected = ["8fold"];
        $actual = Shoop::pipe($payload, FromObject::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }

    public function test_from_string()
    {
        $payload = "8fold";

        $this->start = hrtime(true);
        $expected = ["8", "f", "o", "l", "d"];
        $actual = Shoop::pipe($payload, AsArray::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual, 1.4);

        $this->start = hrtime(true);
        $expected = ["8", "f", "o", "l", "d"];
        $actual = Shoop::pipe($payload, FromString::apply())->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $payload = "I remember. A long time ago.";

        $expected = ["I", "remember.", "A", "long", "time", "ago."];
        $actual = Shoop::pipe($payload, AsArray::applyWith(" "))->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $this->start = hrtime(true);
        $expected = ["I", "remember.", "A long time ago."];
        $actual = Shoop::pipe($payload, FromString::applyWith(" ", true, 3))
            ->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $payload = " I remember. A... ";

        $expected = ["", "I", "remember.", "A...", ""];
        $actual = Shoop::pipe($payload, AsArray::applyWith(" ", true))->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);

        $expected = ["I", "remember.", "A..."];
        $actual = Shoop::pipe($payload, FromString::applyWith(" ", false))
            ->unfold();
        $this->assertEqualsWithPerformance($expected, $actual);
    }
}
