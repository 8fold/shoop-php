<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\AsArray;

use Eightfold\Foldable\Filter;

class FromInt extends Filter
{
    private $start = 0;

    public function __construct(int $start = 0)
    {
        $this->start = $start;
    }

    public function __invoke(int $payload): array
    {
        return ($this->start > $payload)
            ? range($payload, $this->start)
            : range($this->start, $payload);
    }
}