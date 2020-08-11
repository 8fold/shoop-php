<?php
declare(strict_types=1);

namespace Eightfold\Shoop;

use League\Pipeline\Pipeline;

use Eightfold\Shoop\Php\ToArrayFromString;

use Eightfold\Shoop\Php\DivideString;

use Eightfold\Shoop\Php\EndsWithString;

use Eightfold\Shoop\Php\ReverseArray;
use Eightfold\Shoop\Php\ReverseString;

use Eightfold\Shoop\Php\SplitStringOn;

use Eightfold\Shoop\Php\StartsWithString;

use Eightfold\Shoop\Php\StrippedFromString;

use Eightfold\Shoop\Php\TagsStrippedFromString;

use Eightfold\Shoop\Php\ToStringFromArray;
use Eightfold\Shoop\Php\ToStringFromArrayGlue;

class Php
{
// -> Array
    static public function arrayWithoutEmpties(array $payload): array
    {
        // TODO: Make pipeline
        $array = array_filter($payload);
        return array_values($array);
    }

    static public function arrayReversed(array $payload): array
    {
        return (new Pipeline())
            ->pipe(new ReverseArray)
            ->process($payload);
    }

    static public function arrayToString(array $payload, string $glue = ""): string
    {
        $payload = ["array" => $payload, "glue" => $glue];
        return (new Pipeline())
            ->pipe(new ToStringFromArray)
            ->process($payload);
    }

// -> String
    static public function stringSplitAt(
        string $payload,
        int    $splitter       = 0,
        bool   $includeEmpties = true
    )
    {

    }

    static public function stringSplitOn(
        string $payload,
        string $splitter       = "",
        bool   $includeEmpties = true,
        int    $limit          = PHP_INT_MAX
    )
    {
        $payload = [
            "string"         => $payload,
            "splitter"       => $splitter,
            "includeEmpties" => $includeEmpties,
            "limit"          => $limit
        ];
        return (new Pipeline())
            ->pipe(new SplitStringOn)
            ->process($payload);
    }

    static public function stringStrippedOfTags(string $payload, string ...$allowed)
    {
        $payload = ["string" => $payload, "allowed" => $allowed];
        return (new Pipeline())
            ->pipe(new TagsStrippedFromString)
            ->process($payload);
    }

    static public function stringStrippedOf(
        string $payload,
        bool   $fromEnd     = true,
        bool   $fromStart   = true,
        string $charMask    = " \t\n\r\0\x0B"
    )
    {
        $payload = [
            "string"    => $payload,
            "charsMask" => $charMask,
            "fromEnd"   => $fromEnd,
            "fromStart" => $fromStart
        ];
        return (new Pipeline())
            ->pipe(new StrippedFromString)
            ->process($payload);
    }

    static public function stringEndsWith(string $payload, string $suffix): bool
    {
        $payload = ["string" => $payload, "suffix" => $suffix];
        return (new Pipeline())
            ->pipe(new EndsWithString)
            ->process($payload);
    }

    static public function stringStartsWith(string $payload, string $prefix): bool
    {
        $payload = ["string" => $payload, "prefix" => $prefix];
        return (new Pipeline())
            ->pipe(new StartsWithString)
            ->process($payload);
    }

    static public function stringReversed(string $payload): string
    {
        return (new Pipeline())
            ->pipe(new ReverseString)
            ->process($payload);
    }

    static public function stringToLowercaseFirst(string $payload): string
    {
        return lcfirst($payload);
    }

    static public function stringToLowercase(string $payload): string
    {
        return mb_strtolower($payload);
    }

    static public function stringToUppercase(string $payload): string
    {
        return mb_strtoupper($payload);
    }

    static public function stringToArray(string $payload): array
    {
        $pipeline = (new Pipeline())->pipe(new ToArrayFromString);
        return $pipeline->process($payload);
    }
}