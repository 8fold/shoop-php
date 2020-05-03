<?php

namespace Eightfold\Shoop\Interfaces;

use Eightfold\Shoop\ESBool;

interface Has
{
    // Does not make sense on ESBool
    public function has($needle): ESBool;

    // TODO: rename to `hasMember()`
    public function hasKey($member): ESBool;

    // TODO: hasMember & isIn (reverse lookup) & isMemberOf
}
