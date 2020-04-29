<?php

namespace Eightfold\Shoop;

use Eightfold\Shoop\Interfaces\{
    Shooped,
    MathOperations,
    Toggle,
    Shuffle,
    Wrap,
    Sort,
    Split,
    Has,
    Compare
};

use Eightfold\Shoop\Traits\{
    ShoopedImp,
    MathOperationsImp,
    ToggleImp,
    ShuffleImp,
    WrapImp,
    SortImp,
    HasImp,
    CompareImp
};

use Eightfold\Shoop\Helpers\Type;

// TODO: replace(x, y)
class ESString implements
    Shooped,
    MathOperations,
    Toggle,
    Shuffle,
    Wrap,
    Sort,
    Split,
    Has, // TODO: Consider different implementation (array splits on letters, words become issues)
    Compare
{
    use ShoopedImp, MathOperationsImp, ToggleImp, ShuffleImp, WrapImp, SortImp, HasImp, CompareImp;

    public function __construct($string)
    {
        if (is_string($string)) {
            $this->value = $string;

        } elseif (is_a($string, ESString::class)) {
            $this->value = $string->unfold();

        } else {
            $this->value = "";

        }
    }

// - Type Juggling
// - PHP single-method interfaces
    public function count(): ESInt
    {
        return Shoop::int(strlen($this->unfold()));
    }

// - Math language
    public function multiply($int)
    {
        $int = Type::sanitizeType($int, ESInt::class)->unfold();
        return Shoop::string(str_repeat($this->unfold(), $int));
    }

    public function divide($divisor = null, $removeEmpties = true)
    {
        if ($divisor === null) {
            return Shoop::array([]);
        }

        $divisor = Type::sanitizeType($divisor, ESString::class);
        $removeEmpties = Type::sanitizeType($removeEmpties, ESBool::class);

        $exploded = explode($divisor, $this);
        $shooped = Shoop::array($exploded);

        if ($removeEmpties->unfold()) {
            $shooped = $shooped->noEmpties();
        }
        return $shooped;
    }

// - Comparison
// - Getters
    public function set($value)
    {
        $value = Type::sanitizeType($value, ESString::class)->unfold();
        return self::fold($value);
    }

    public function get($member)
    {
        $member = Type::sanitizeType($member, ESInt::class)->unfold();
        if ($this->offsetExists($member)) {
            $m = $this->value[$member];
            return ((Type::isPhp($m))) ? Type::sanitizeType($m) : $m;
        }
        trigger_error("Undefined index or member.");
    }

    // TODO: Could this be promoted to the hasImp - or a global contract?
    public function isIn($haystack): ESBool
    {
        $bool = false;
        foreach ($this->array() as $needle) {
            if ($this->hasUnfolded($needle)) {
                $bool = true;
                break;
            }
        }
        return Shoop::bool($bool);
    }

// - Manipulate
    public function toggle($preserveMembers = true)
    {
        return $this->array()->toggle()->join("");
    }

    public function sort(?string ...$flags)
    {
        if (count($flags) === 0) {
            $flags = ["case"];
        }
        return $this->array()->sort(...$flags)->join("");
    }

    public function start(...$prefixes)
    {
        $combined = implode('', $prefixes);
        return Shoop::string($combined . $this->unfold());
    }

    // TODO: test and verify occurences working
    // str_replace is the wrong function to use for this
    public function replace($search, $replace, $occurences = 0): ESString
    {
        return Shoop::string(str_replace($search, $replace, $this->unfold(), $occurences));
    }

// - Wrap
    public function startsWith($needle): ESBool
    {
        $needle = Type::sanitizeType($needle, ESString::class);
        $substring = substr($this->unfold(), 0, $needle->countUnfolded());
        return Shoop::bool($substring === $needle->unfold());
    }

    public function endsWith($needle): ESBool
    {
        $needle = Type::sanitizeType($needle, ESString::class)->toggle();
        $reversed = $this->toggle();
        return $reversed->startsWith($needle);
    }

// - Split
    public function split($splitter = 1, $splits = 2): ESArray
    {
        $splitter = Type::sanitizeType($splitter, ESString::class)->unfold();
        $splits = Type::sanitizeType($splits, ESInt::class)->unfold();
        return Shoop::array(explode($splitter, $this->unfold(), $splits));
    }

// - Replace
// - Other
    // public function hasMember($member): ESBool
    // {
    //     $member = Type::sanitizeType($member, ESString::class)->unfold();
    //     return Shoop::bool($this->offsetExists($member));
    // }

    public function lowerFirst(): ESString
    {
        // ?? lower(1, 3, 4) : lower("even") : lower("odd")
        return Shoop::string(lcfirst($this->value));
    }

    public function uppercase(): ESString
    {
        return Shoop::string(strtoupper($this->value));
    }

    public function pathContent()
    {
        if (is_file($this->unfold())) {
            return Shoop::string(file_get_contents($this->unfold()));
        }
        return Shoop::string("");
    }

    public function writeToPath($path)
    {
        $path = Type::sanitizeType($path, ESString::class)->unfold();
        return Shoop::int(file_put_contents($path, $this->unfold()));
    }

// - Transforms
// - Callers
// -> Array Access
}
