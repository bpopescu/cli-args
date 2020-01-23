<?php

namespace bpopescu\CliArgs\Tests;

use bpopescu\CliArgs\CliArgs;
use PHPUnit\Framework\TestCase;

class CliArgsTest extends TestCase
{
    public function parseDP()
    {
        return [
            [['--a:b']],
            [['--a=b']],
            [['-a:b']],
            [['-a=b']],
        ];
    }

    /**
     * @dataProvider parseDP
     * @param array $arguments
     */
    public function testParse(array $arguments = [])
    {
        $cliArgs = (new CliArgs())->parse($arguments);
        $this->assertArrayHasKey('a', $cliArgs);
        $this->assertEquals('b', $cliArgs['a']);
    }

    public function testParseSimple()
    {
        $cliArgs = (new CliArgs())->parse(['b', 'c']);
        $this->assertEquals(['b', 'c'], $cliArgs);
    }

    public function testParseOneLetter()
    {
        $cliArgs = (new CliArgs())->parse(['-a', '-b']);
        $this->assertArrayHasKey('a', $cliArgs);
        $this->assertTrue($cliArgs['a']);
        $this->assertArrayHasKey('b', $cliArgs);
        $this->assertTrue($cliArgs['b']);
    }

    public function testParseOneLetterExtended()
    {
        $cliArgs = (new CliArgs())->parse(['-a', '-b', 'c']);
        $this->assertArrayHasKey('a', $cliArgs);
        $this->assertTrue($cliArgs['a']);
        $this->assertArrayHasKey('b', $cliArgs);
        $this->assertTrue(in_array('c', $cliArgs));
    }

    public function testParseMultipleLetters()
    {
        $cliArgs = (new CliArgs())->parse(['-ab']);
        $this->assertArrayHasKey('a', $cliArgs);
        $this->assertTrue($cliArgs['a']);
        $this->assertArrayHasKey('b', $cliArgs);
        $this->assertTrue($cliArgs['b']);
    }
}