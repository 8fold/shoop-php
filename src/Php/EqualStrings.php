<?php
declare(strict_types=1);

namespace Eightfold\Shoop\Php;

use Eightfold\Foldable\Filter;

class EqualStrings extends Filter
{
    private $compare = "";

    public function __construct(string $compare = "")
    {
        $this->compare = $compare;
    }

    public function __invoke(string $using): bool
    {
        return $using === $this->compare;
    }
}
