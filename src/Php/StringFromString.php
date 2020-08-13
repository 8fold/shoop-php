<?php
declare(strict_types=1);

namespace Eightfold\Shoop\Php;

use Eightfold\Foldable\Bend;

class StringFromString extends Bend
{
    private $start = 0;
    private $length = 0;

    static public function bendWith(...$args)
    {
        return new static(...$args);
    }

    public function __construct(int $start = 0, int $length = 0)
    {
        $this->start = $start;
        $this->length = $length;
    }

    public function __invoke(string $payload): string
    {
        if ($this->length === 0) {
            return substr($payload, $this->start);
        }
        return substr($payload, $this->start, $this->length);
    }
}