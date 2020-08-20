<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\PipeFilters\Minus;

trait StrippableImp
{
    // TODO: PHP 8.0 bool|ESBoolean, bool|ESBoolean, string|ESString
    public function strip(
        $charMask  = " \t\n\r\0\x0B",
        $fromEnd   = true,
        $fromStart = true
    ): self
    {
        $string = Shoop::pipe($this->main,
            Minus::applyWith($fromEnd, $fromStart, $charMask)
        )->unfold();
        return static::fold($string);
    }

    // TODO: PHP 8.0 - Stringable
    public function stripTags(...$allow): self
    {
        $type = gettype($this->main);
        $method = "{$type}StrippedOfTags";
        $string = Php::{$method}($this->main, ...$allow);
        return static::fold($string);
    }

    // TODO: PHP 8.0 - int|ESInteger
    public function stripFirst($length = 1): self
    {
        $type = gettype($this->main);
        $method = "{$type}StrippedOfFirst";
        $string = Php::{$method}($this->main, $length);
        return static::fold($string);
    }

    // TODO: PHP 8.0 - int|ESInteger
    public function stripLast($length = 1): self
    {
        $type = gettype($this->main);
        $method = "{$type}StrippedOfLast";
        $string = Php::{$method}($this->main, $length);
        return static::fold($string);
    }
}