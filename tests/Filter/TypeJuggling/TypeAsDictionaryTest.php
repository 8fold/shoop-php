<?php

namespace Eightfold\Shoop\Tests\Filter\TypeJuggling;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use \stdClass;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\Filter\TypeJuggling\AsDictionary;

use Eightfold\Shoop\FilterContracts\Interfaces\Addable;
use Eightfold\Shoop\FilterContracts\Interfaces\Subtractable;
use Eightfold\Shoop\FilterContracts\Interfaces\Associable;
use Eightfold\Shoop\FilterContracts\Interfaces\Falsifiable;

/**
 * @group TypeChecking
 *
 * @group  AsDictionary
 *
 * @group 1.0.0
 */
class TypeAsDictionaryTest extends TestCase
{
    /**
     * @test
     */
    public function objects()
    {
        AssertEquals::applyWith(
            ["public" => "tuple"],
            "array",
            1.97, // 1.67,
            173
        )->unfoldUsing(
            AsDictionary::fromObject(
                new class {
                    public $public = "tuple";
                    private $private = "private";
                }
            )
        );

        AssertEquals::applyWith(
            ["public" => "object"],
            "array",
            0.04,
            1
        )->unfoldUsing(
            AsDictionary::fromObject(
                new class {
                    public $public = "object";
                    private $private = "private";
                    public function someAction()
                    {
                        return false;
                    }
                }
            )
        );

        AssertEquals::applyWith(
            ["dictionary" => true],
            "array",
            8.69,
            60
        )->unfoldUsing(
            AsDictionary::fromObject(
                new class implements Associable {
                    public $public = "object";
                    private $private = "private";

                    public function plus($value): Addable
                    {
                        return Shoop::this([]);
                    }

                    public function minus($value): Subtractable
                    {
                        return Shoop::this([]);
                    }

                    public function asDictionary(): Associable
                    {
                        return Shoop::this(["dictionary" => true]);
                    }

                    public function efToDictionary(): array
                    {
                        return $this->asDictionary()->unfold();
                    }

                    public function has($member): Falsifiable
                    {
                        return Shoop::this(true);
                    }

                    public function hasAt($member): Falsifiable
                    {
                        return Shoop::this(false);
                    }

                    public function offsetExists($offset): bool
                    {
                        return $this->hasAt($offset)->unfold();
                    }

                    public function at($member)
                    {
                        return false;
                    }

                    public function offsetGet($offset)
                    {
                        return $this->at($offset)->unfold();
                    }

                    public function plusAt($value, $member): Associable
                    {
                        $this->main[$member] = $value;
                        return $this;
                    }

                    public function offsetSet($offset, $value): void
                    {
                        $this->plusAt($value, $offset);
                    }

                    public function minusAt($member): Associable
                    {
                        unset($this->main[$member]);
                        return $this;
                    }

                    public function offsetUnset($offset): void
                    {
                        $this->minusAt($offset);
                    }

                    public function rewind(): void
                    {
                        rewind($this->main);
                    }

                    public function valid(): bool
                    {
                        $member = key($this->main);
                        return array_key_exists($member, $this->main);
                    }

                    public function current()
                    {
                        $member = key($temp);
                        return $temp[$member];
                    }

                    public function key()
                    {
                        key($this->main);
                    }

                    public function next(): void
                    {
                        next($this->main);
                    }
                }
            )
        );
    }
}