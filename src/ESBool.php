<?php

namespace Eightfold\Shoop;

use Eightfold\Shoop\Helpers\Type;

use Eightfold\Shoop\ESInt;

use Eightfold\Shoop\Interfaces\{
    Shooped,
    Compare,
    Toggle,
    Shuffle
};

use Eightfold\Shoop\Traits\{
    ShoopedImp,
    CompareImp,
    ToggleImp,
    ShuffleImp
};

class ESBool implements Shooped, Toggle, Shuffle, Compare
{
    use ShoopedImp, ToggleImp, ShuffleImp, CompareImp;

    public function __construct($bool)
    {
        if (is_bool($bool)) {
            $this->value = $bool;

        } elseif (is_a($bool, ESBool::class)) {
            $this->value = $bool->unfold();

        } else {
            $this->value = false;

        }
    }

// - Type Juggling
// - PHP single-method interfaces
// - Manipulate
    public function toggle($preserveMembers = true): ESBool
    {
        return ESBool::fold(! $this->unfold());
    }

// - Math language
// - Getters
    public function get()
    {
        return $this;
    }

// - Comparison
// - Other
    public function set($value)
    {
        $value = Type::sanitizeType($value, ESBool::class)->unfold();
        return self::fold($value);
    }

    public function not(): ESBool
    {
        return $this->toggle();
    }

    public function or($bool): ESBool
    {
        $bool = Type::sanitizeType($bool, ESBool::class);
        return Shoop::bool($this->unfold() || $bool->unfold());
    }

    public function and($bool): ESBool
    {
        $bool = Type::sanitizeType($bool, ESBool::class);
        return Shoop::bool($this->unfold() && $bool->unfold());
    }

// -> Array Access
}
