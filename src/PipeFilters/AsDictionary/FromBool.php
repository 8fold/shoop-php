<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\AsDictionary;

use Eightfold\Foldable\Filter;

class FromBool extends Filter
{
    public function __invoke(bool $payload): array
    {
        return ($payload === true)
            ? ["true" => true, "false" => false]
            : ["true" => false, "false" => true];
    }
}