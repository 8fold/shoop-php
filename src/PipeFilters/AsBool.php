<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\PipeFilters\AsBool\FromArray;
use Eightfold\Shoop\PipeFilters\AsBool\FromInt;
use Eightfold\Shoop\PipeFilters\AsBool\FromJson;
use Eightfold\Shoop\PipeFilters\AsBool\FromObject;
use Eightfold\Shoop\PipeFilters\AsBool\FromString;
use Eightfold\Shoop\PipeFilters\IsJson;

class AsBool extends Filter
{
    public function __invoke($using): Bool
    {
        if (is_bool($using)) {
            return $using;

        } elseif (is_int($using)) {
            return Shoop::pipe($using, FromInt::apply())->unfold();

        } elseif (is_object($using)) {
            return Shoop::pipe($using, FromObject::apply())->unfold();

        } elseif (is_array($using)) {
            return Shoop::pipe($using, FromArray::apply())->unfold();

        } elseif (is_string($using)) {
            return (Shoop::pipe($using, IsJson::apply())->unfold())
                ? Shoop::pipe($using, FromJson::apply())->unfold()
                : Shoop::pipe($using, FromString::apply())->unfold();

        }
        return false;
    }
}
