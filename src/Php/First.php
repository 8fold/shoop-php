<?php
declare(strict_types=1);

namespace Eightfold\Shoop\Php;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\Php\StringIsJson;

class First extends Filter
{
    private $length = 1;

    public function __construct(int $length = 1)
    {
        $this->length = $length;
    }

    public function __invoke($payload)
    {
        if (is_bool($payload)) {
            // ToArrayFromBoolean
        } elseif (is_int($payload)) {
            // return Shoop::pipe($payload, ToArrayFromInteger::apply())->unfold();

        } elseif (is_object($payload)) {
            // ToArrayFromObject

        } elseif (is_array($payload)) {
            return ($first = array_shift($payload) === null) ? false : $first;
            // return Shoop::pipe($payload, ValuesFromArray::apply())->unfold();

        } elseif (is_string($payload)) {
            $isJson = Shoop::pipe($payload, StringIsJson::apply())->unfold();
            if ($isJson) {
                // return Shoop::pipe($payload, ToArrayFromJson::apply())
                //     ->unfold();
            }
            return $payload . $this->value;
        }
        return [];
    }
}
