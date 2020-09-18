<?php
declare(strict_types=1);

namespace Eightfold\Shoop\Filter;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Filter\Count;
use Eightfold\Shoop\Filter\Is\IsGreaterThan;

/**
 * @todo invocation
 */
class CountIsGreaterThan extends Filter
{
    public function __invoke(string $using): bool
    {
    }

    // TODO: PHP 8 - , int|float
    static public function fromString(string $using, $target): bool
    {
        $count = Count::fromString($using);
        return IsGreaterThan::fromNumber($count, $target);
    }

    static public function fromList(array $using, $target): bool
    {
        $count = Count::fromList($using);
        return IsGreaterThan::fromNumber($count, $target);
    }
}
