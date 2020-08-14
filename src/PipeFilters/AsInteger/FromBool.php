<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\AsInteger;

use Eightfold\Foldable\Filter;

class FromBool extends Filter
{
    public function __invoke(bool $using): int
    {
        return (int) $using;
    }
}
