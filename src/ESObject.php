<?php

namespace Eightfold\Shoop;

use Eightfold\Shoop\Helpers\Type;

use Eightfold\Shoop\Interfaces\{
    Shooped,
    Countable,
    Has
};

use Eightfold\Shoop\Traits\{
    ShoopedImp,
    CountableImp,
    HasImp
};

class ESObject implements Shooped, Countable, Has
{
    use ShoopedImp, CountableImp, HasImp;

    public function __construct($object)
    {
        if (is_object($object)) {
            $this->value = $object;

        } elseif (is_a($object, ESObject::class)) {
            $this->value = $object->unfold();

        } elseif (Type::is($object, ESArray::class, ESDictionary::class)) {
            $this->value = (object) $object->unfold();

        } elseif (is_array($object)) {
            $this->value = (object) $object;

        } else {
            $this->value = new \stdClass();

        }
    }

// - Type Juggling
    public function string(): ESString
    {
        $printed = print_r($this->unfold(), true);
        $oneLine = preg_replace('/\s+/', ' ', $printed);
        $commas = str_replace(
            [" [", " ) ", " (, "],
            [", [", ")", "("],
            $oneLine);
        return Shoop::string(trim($commas));
    }

    public function array(): ESArray
    {
        $array = (array) $this->value;
        return Shoop::dictionary($array)->array();
    }

    public function dictionary(): ESDictionary
    {
        if (isset($this->dictionary)) {
            return $this->dictionary;
        }
        $this->dictionary = (array) $this->unfold();

        return Shoop::dictionary($this->dictionary);
    }

    public function object(): ESObject
    {
        return Shoop::object($this->unfold());
    }

    public function bool(): ESBool
    {
        return ESBool::fold(Type::isEmpty($this->array()))->toggle();
    }

    public function json(): ESJson
    {
        return Shoop::json(json_encode($this->unfold()));
    }

// - PHP single-method interfaces
// - Manipulate
// - Search
// - Math language
    public function multiply($int)
    {
        $int = Type::sanitizeType($int, ESInt::class)->unfold();
        $array = [];
        for ($i = 0; $i < $int; $i++) {
            $array[] = $this;
        }
        return Shoop::array($array);
    }

    public function plus(...$args)
    {
        $count = count($args);
        if ($count < 2) {
            $className = ESObject::class;
            trigger_error(
                "{$className}::plus() expects two arguments. {$count->unfold()} given."
            );
        }
        $member = Type::sanitizeType($args[0], ESString::class)->unfold();
        $value = $args[1];
        $overwrite = true;
        if ($count === 3 && $args[2] !== null && Type::is($args[2], ESBool::class, "bool")) {
            $overwrite = Type::sanitizeType($args[2], ESBool::class)->unfold();
        }
        return $this->set($member, $value, $overwrite);
    }

    public function minus(...$args): ESObject
    {
        $stash = (array) $this->value;
        foreach ($args as $delete) {
            $member = Type::sanitizeType($delete, ESString::class)->unfold();
            unset($stash[$member]);
        }
        return Shoop::object((object) $stash);
    }

    public function divide($value = null)
    {
        return $this->dictionary()
            ->divide()
            ->object()
            ->rename("keys", "members");
    }

// - Other
    public function set($member, $value, $overwrite = true)
    {
        $member = Type::sanitizeType($member, ESString::class)->unfold();
        $overwrite = Type::sanitizeType($overwrite, ESBool::class)->unfold();

        $cast = (array) $this->value;
        if (! $overwrite && $this->hasMember($member)) {
            $currentValue = $cast[$member];
            if (is_array($currentValue)) {
                $currentValue[] = $value;

            } else {
                $currentValue = [$currentValue, $value];

            }

            $cast[$member] = $currentValue;
            return static::fold($cast);
        }
        $merged = array_merge($cast, [$member => $value]);
        return static::fold($merged);
    }

    public function get($member)
    {
        $member = Type::sanitizeType($member, ESString::class)->unfold();
        $v = (array) $this->unfold();
        if (isset($v[$member])) {
            $m = $v[$member];
            // TODO: Return sanitized type - not working ??
            return ((Type::isPhp($m))) ? Type::sanitizeType($m) : $m;
        }
        return null;
    }

    public function has($value): ESBool
    {
        // Check if value exists
        return Shoop::bool($this->array()->has($value));
    }

    public function hasMember($member): ESBool
    {
        if (Type::isArray($member)) {
            $member = $member[0];
        }
        return Shoop::bool(isset($this->value->{$member}));
    }

    private function rename(string $current, string $new)
    {
        $value = $this->{$current};
        $this->{$new} = $value;
        unset($this->{$current});
        return $this;
    }

// - Transforms
// - Callers
    // public function __call($name, $args = [])
    // {
    //     $name = Shoop::string($name);
    //     if ($name->startsWith("set")->unfold()) {
    //         $member = $name->minus("set")->lowerFirst();
    //         $overwrite = (isset($args[1])) ? $args[1] : true;
    //         $value = (isset($args[0])) ? $args[0] : null;

    //         return $this->set($member, $value, $overwrite);

    //     } elseif ($name->endsWith("Unfolded")->unfold()) {
    //         $member = $name->minus("Unfolded");
    //         if (method_exists($this, $member)) {
    //             $value = $this->{$member->unfold()}($args);
    //             if (Type::isShooped($value)) {
    //                 return $value->unfold();
    //             }
    //         }
    //         $value = $this->get($member);
    //         $return = (isset($value) && Type::isShooped($value))
    //             ? $value->unfold()
    //             : $value;
    //         return $return;

    //     } else {
    //         return Type::sanitizeType($this->get($name->minus("get")->lowerFirst()));

    //     }
    // }

// - Setters/Getters
    public function __set(string $name, $value)
    {
        $name = Type::sanitizeType($name, ESString::class)->unfold();
        $this->value->{$name} = $value;
    }

    public function __get (string $name)
    {
        if ($this->hasMember($name)->unfold()) {
            return $this->value->{$name};
        }
        return null;
    }

    public function __isset(string $name): bool
    {
        return $this->hasMember($name)->unfold();
    }

    public function __unset(string $name): void
    {
        unset($this->value->{$name});
    }

// -> Array Access
    // public function offsetExists($offset): bool {}

    // public function offsetGet($offset) {}

    // public function offsetSet($offset, $value): void {}

    // public function offsetUnset($offset): void {}
    // public function offsetExists($offset): bool
    // {
    //     return $this->has($offset)->unfold();
    // }

    // public function offsetGet($offset)
    // {
    //     // TODO: Create null class ??
    //     if ($this->get($offset) === null) {
    //         return null;
    //     }
    //     return $this->get($offset);
    // }

    // public function offsetSet($offset, $value): ESObject
    // {
    //     return $this->set($offset, $value);
    // }

    // public function offsetUnset($offset): void
    // {
    //     $this->__unset($offset);
    // }

//-> Iterator
    // private $tempDict;

    // /**
    //  * rewind() -> valid() -> current() -> key() -> next() -> valid()...repeat
    //  *
    //  * Same implementation for Object, Dictionary, JSON
    //  *
    //  * @return [type] [description]
    //  */
    // public function rewind()
    // {
    //     $this->tempDict = $this->dictionary()->unfold();
    // }

    // public function valid(): bool
    // {
    //     if (! isset($this->tempDict)) {
    //         $this->rewind();
    //     }
    //     return $this->hasMemberUnfolded(key($this->tempDict));
    // }

    // public function current()
    // {
    //     if (! isset($this->tempDict)) {
    //         $this->rewind();
    //     }
    //     $dict = $this->tempDict;
    //     $key = key($dict);
    //     return $dict[$key];
    // }

    // public function key()
    // {
    //     if (! isset($this->tempDict)) {
    //         $this->rewind();
    //     }
    //     $dict = $this->tempDict;
    //     $key = key($dict);
    //     if (is_int($key)) {
    //         return Type::sanitizeType($key, ESInt::class, "int")->unfold();
    //     }
    //     return Type::sanitizeType($key, ESString::class, "string")->unfold();
    // }

    // public function next()
    // {
    //     if (! isset($this->tempDict)) {
    //         $this->rewind();
    //     }
    //     next($this->tempDict);
    // }
}