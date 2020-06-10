<?php

namespace Eightfold\Shoop\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\{
    Shoop,
    ESArray,
    Helpers\Type
};

class StringTest extends TestCase
{
    /**
     * The `replace()` method on ESString replaces instances of characters with the given characters. You can also limit the number of replacements made.
     *
     * @return Eightfold\Shoop\ESString
     */
    public function testReplace()
    {
        $base = "Hello, World!";
        $expected = "Hero, World!";
        $actual = Shoop::string($base)
            ->replace(["ll" => "r"]);
        $this->assertEquals($expected, $actual->unfold());

        $base = "abx, xab, bax";
        $expected = "abc, cab, bac";
        $actual = Shoop::string($base)->replace(["x" => "c"]);
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testReplaceRange()
    {
        $string = "ABCDEFGH:/MNRPQR/";
        $expected = "bob:/MNRPQR/";
        $actual = Shoop::string($string)->replaceRange("bob", 0, 8);
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testTrim()
    {
        $base = " \tTrust \n";
        $expected = "Trust";
        $actual = Shoop::string($base)->trim;
        $this->assertEquals($expected, $actual);

        $expected = " \tTrust";
        $actual = Shoop::string($base)->trimUnfolded(false);
        $this->assertEquals($expected, $actual);

        $expected = "Trust \n";
        $actual = Shoop::string($base)->trimUnfolded(true, false);
        $this->assertEquals($expected, $actual);

        $expected = " \tTrust \n";
        $actual = Shoop::string($base)->trimUnfolded(false, false);
        $this->assertEquals($expected, $actual);

        $base = " \tTrust\n ";
        $expected = "\tTrust\n";
        $actual = Shoop::string($base)->trimUnfolded(true, true, " ");
        $this->assertEquals($expected, $actual);
    }

    /**
     * The `lowerFirst()` method on ESString returns the original value after ensuring the first character is lowercase.
     *
     * @return Eightfold\Shoop\ESString
     */
    public function testLowerFirst()
    {
        $base = "HELLO!";
        $expected = "hELLO!";
        $actual = Shoop::string($base)->lowerFirst();
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * The `uppercase()` method on ESString returns the original value after ensuring *all* letters are upper case.
     *
     * @return Eightfold\Shoop\ESString
     */
    public function testUppercase()
    {
        $base = "hello!";
        $expected = "HELLO!";
        $actual = Shoop::string($base)->uppercase();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testPathContent()
    {
        $base = __DIR__;
        $actual = Shoop::string($base)->divide("/")
            ->plus("_data", "test-file.md")->join("/")->pathContent();
        $this->assertEquals("Test file content", $actual->unfold());

        $expected = [$base ."/_data/test-file.md", $base ."/_data/test-folder"];
        $actual = Shoop::string($base)->divide("/")
            ->plus("_data")->join("/")->pathContent();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testIsDirIsFile()
    {
        $base = __DIR__;

        $expected = [true, false];
        $actual = Shoop::string($base)->divide("/")
            ->plus("_data")->join("/")->pathContent()->each(function($path) {
                return Shoop::string($path)->isFile();
            });
        $this->assertEquals($expected, $actual->unfold());

        $expected = [false, true];
        $actual = Shoop::string($base)->divide("/")
            ->plus("_data")->join("/")->pathContent()->each(function($path) {
                return Shoop::string($path)->isFolder();
            });
        $this->assertEquals($expected, $actual->unfold());

        $actual = Shoop::string($base)->divide("/")
            ->plus("_data", "test-file.quote")->join("/")->isFile();
        $this->assertFalse($actual->unfold());

        $actual = Shoop::string($base)->divide("/")
            ->plus("_data", "test-file.md")->join("/")->isFile();
        $this->assertTrue($actual->unfold());

        $actual = Shoop::string($base)->divide("/")
            ->plus("_data", "test-file.quote")->join("/")
            ->isFile(function($result) {
                return Shoop::this($result);
            });
        $this->assertFalse($actual->unfold());

        $actual = Shoop::string($base)->divide("/")
            ->plus("_data", "test-file.md")->join("/")
            ->isFile(function($result) {
                return Shoop::this($result);
            });
        $this->assertTrue($actual->unfold());

        $actual = Shoop::string($base)->divide("/")
            ->plus("_data", "test-file.md")->join("/")
            ->isFile(function($result, $value) {
                return (! $result)
                    ? Shoop::string("")
                    : Shoop::string($value)->divide(".")->last();
            });
        $this->assertEquals("md", $actual->unfold());

        $actual = Shoop::string($base)->divide("/")
            ->plus("_data", "test-file.quote")->join("/")
            ->isFile(function($result, $value) {
                return (! $result)
                    ? Shoop::string("")
                    : Shoop::string($value)->divide(".")->last();
            });
        $this->assertEquals("", $actual->unfold());
    }

    public function testDropTags()
    {
        $base = "<p>Hello!</p>";
        $expected = "Hello!";
        $actual = Shoop::string($base)->dropTags();
        $this->assertEquals($expected, $actual->unfold());

        $base = "<p>Hello!</p>";
        $expected = "<p>Hello!</p>";
        $actual = Shoop::string($base)->dropTags("<p>");
        $this->assertEquals($expected, $actual->unfold());
    }
}
