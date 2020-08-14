<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\AsObject;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\PipeFilters\AsDictionary;

class FromString extends Filter
{
    public function __invoke(string $payload): object
    {
        return (object) Shoop::pipe($payload, AsDictionary::apply())->unfold();
    }
}