<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\AsObject;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\PipeFilters\AsDictionary;
use Eightfold\Shoop\PipeFilters\AsObject;

class FromInt extends Filter
{
    public function __invoke(int $payload): object
    {
        return Shoop::pipe($payload,
            AsDictionary::applyWith(...$this->args(true)),
            AsObject::apply()
        )->unfold();
    }
}
