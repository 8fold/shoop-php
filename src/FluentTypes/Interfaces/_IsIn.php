<?php

namespace Eightfold\Shoop\FluentTypes\Interfaces;

use \Closure;

use Eightfold\Shoop\FluentTypes\ESBoolean;

interface _IsIn
{
    public function isIn($haystack, Closure $closure = null);

    public function isNotIn($haystack, Closure $closure = null);
}