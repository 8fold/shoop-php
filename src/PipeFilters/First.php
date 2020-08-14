<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\PipeFilters\First\FromArray;
use Eightfold\Shoop\PipeFilters\First\FromInt;
use Eightfold\Shoop\PipeFilters\First\FromJson;
use Eightfold\Shoop\PipeFilters\First\FromObject;
use Eightfold\Shoop\PipeFilters\First\FromString;

use Eightfold\Shoop\PipeFilters\IsJson;

class First extends Filter
{
    public function __invoke($payload)
    {
        if (is_bool($payload)) {
            return [];

        } elseif (is_int($payload)) {
            return Shoop::pipe($payload,
                FromInt::applyWith(...$this->args(true))
            )->unfold();

        } elseif (is_object($payload)) {
            return Shoop::pipe($payload,
                FromObject::applyWith(...$this->args(true))
            )->unfold();

        } elseif (is_array($payload)) {
            return Shoop::pipe($payload,
                FromArray::applyWith(...$this->args(true))
            )->unfold();

        } elseif (is_string($payload)) {
            if (Shoop::pipe($payload, IsJson::apply())->unfold()) {
                return Shoop::pipe($payload,
                    FromJson::applyWith(...$this->args())
                )->unfold();

            }

            return Shoop::pipe($payload,
                FromString::applyWith(...$this->args(true))
            )->unfold();

        }
        return [];
    }
}
