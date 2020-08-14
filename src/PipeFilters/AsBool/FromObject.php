<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\AsBool;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\PipeFilters\AsInt;
use Eightfold\Shoop\PipeFilters\IsNot;

class FromObject extends Filter
{
    public function __invoke(object $payload): bool
    {
        return Shoop::pipe($payload,
            AsInt::apply(),
            IsNot::applyWith(0)
        )->unfold();
    }
}