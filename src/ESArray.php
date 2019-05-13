<?php

namespace Eightfold\Shoop;

use Illuminate\Support\Arr as IlluminateArray;

use Eightfold\Shoop\{
    ESString,
    ESBool
};

use Eightfold\Shoop\Interfaces\{
    Wrappable,
    Equatable,
    Describable,
    Randomizable,
    Storeable,
    EquatableImp,
    RandomizableImp,
    StoreableImp
};

class ESArray implements
    Wrappable,
    Equatable,
    Describable,
    Randomizable,
    Storeable
{
    use EquatableImp,
        RandomizableImp,
        StoreableImp;

    private $value = [];

    static public function wrap(...$args): ESArray
    {
        return new ESArray(IlluminateArray::flatten($args));
    }

    static public function wrapArray(array $array): ESArray
    {
        return new ESArray($array);
    }

    public function __construct(array $array)
    {
        $this->value = $array;
    }

    public function unwrap(): array
    {
        return $this->value;
    }

    private function array(): ESArray
    {
        return ESArray::wrap($this->value);
    }

//-> Describable
    public function description(): ESString
    {
        $valuesAsString = implode(", ", $this->value);
        return ESString::wrap("[{$valuesAsString}]");
    }
}
