<?php

namespace Eightfold\Shoop;

use Eightfold\Shoop\Helpers\{
    Type,
    PhpBool
};

use Eightfold\Shoop\ESInt;

use Eightfold\Shoop\Interfaces\{
    Shooped,
    Toggle,
    IsIn
};

use Eightfold\Shoop\Traits\{
    ShoopedImp,
    ToggleImp,
    IsInImp
};

class ESBool implements Shooped, Toggle, IsIn
{
    use ShoopedImp, ToggleImp, IsInImp;

    static public function to(ESBool $instance, string $className)
    {
        if ($className === ESArray::class) {
            return PhpBool::toIndexedArray($instance->main());

        } elseif ($className === ESBool::class) {
            return $instance->main();

        } elseif ($className === ESDictionary::class) {
            return PhpBool::toAssociativeArray($instance->main());

        } elseif ($className === ESInt::class) {
            return PhpBool::toInt($instance->main());

        } elseif ($className === ESJson::class) {
            return PhpBool::toJson($instance->main());

        } elseif ($className === ESObject::class) {
            return PhpBool::toObject($instance->main());

        } elseif ($className === ESString::class) {
            return PhpBool::toString($instance->main());

        }
    }

    static public function processedMain($main): bool
    {
        if (is_bool($main)) {
            $main = $main;

        } elseif (is_a($main, ESBool::class)) {
            $main = $main->unfold();

        } else {
            $main = false;

        }
        return $main;
    }

    public function not(): ESBool
    {
        return $this->toggle();
    }

    /**
     * @deprecated
     */
    public function or($bool): ESBool
    {
        $bool = Type::sanitizeType($bool, ESBool::class);
        return Shoop::bool($this->unfold() || $bool->unfold());
    }

    /**
     * @deprecated
     */
    public function and($bool): ESBool
    {
        $bool = Type::sanitizeType($bool, ESBool::class);
        return Shoop::bool($this->unfold() && $bool->unfold());
    }
}
