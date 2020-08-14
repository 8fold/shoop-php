<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\PullFirst;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;

class FromArray extends Filter
{
    private $length = 1;

    public function __construct(int $length = 1)
    {
        $this->length = $length;
    }

    public function __invoke(array $using): array
    {
        return array_slice($using, 0, $this->length);
    }
}
