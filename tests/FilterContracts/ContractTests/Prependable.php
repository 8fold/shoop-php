<?php

namespace Eightfold\Shoop\Tests\FilterContracts\ContractTests;

use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shooped;

trait Prependable
{
    /**
     * @test
     */
    public function prepend()
    {
        AssertEquals::applyWith(
            true,
            "boolean",
            0.3,
            14
        )->unfoldUsing(
            Shooped::fold(false)->prepend(1)
        );

        AssertEquals::applyWith(
            false,
            "boolean"
        )->unfoldUsing(
            Shooped::fold(false)->prepend(false)
        );

        AssertEquals::applyWith(
            2,
            "integer"
        )->unfoldUsing(
            Shooped::fold(1)->prepend(1)
        );

        AssertEquals::applyWith(
            0.5,
            "double"
        )->unfoldUsing(
            Shooped::fold(1.5)->prepend(-1)
        );

        AssertEquals::applyWith(
            [2, 3, 1],
            "array"
        )->unfoldUsing(
            Shooped::fold([1])->prepend([2, 3])
        );

        AssertEquals::applyWith(
            "!8fold",
            "string"
        )->unfoldUsing(
            Shooped::fold("8fold")->prepend("!")
        );

        AssertEquals::applyWith(
            (object) ["b" => 2, "c" => 3, "a" => 1],
            "object"
        )->unfoldUsing(
            Shooped::fold((object) ["a" => 1])->prepend(["b" => 2, "c" => 3])
        );

        AssertEquals::applyWith(
            (object) ["a" => 1, "c" => 3],
            "object"
        )->unfoldUsing(
            Shooped::fold((object) ["a" => 1])->prepend((object) ["a" => 2, "c" => 3])
        );

        AssertEquals::applyWith(
            (object) ["a" => 1, "i0" => 3],
            "object"
        )->unfoldUsing(
            Shooped::fold((object) ["a" => 1])->prepend(3)
        );

        // TODO: Objects
    }
}
