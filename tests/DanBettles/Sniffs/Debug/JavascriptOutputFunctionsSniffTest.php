<?php

namespace DanBettles\PhpCodeSnifferStandard\Tests\DanBettles\Sniffs\Debug;

use DanBettles\PhpCodeSnifferStandard\Tests\TestCase;

class JavascriptOutputFunctionsSniffTest extends TestCase
{
    public function testSniffEmitsWarnings()
    {
        $this->assertEquals([
            1 => 1,
            6 => 1,
            7 => 1,
            11 => 1,
            15 => 1,
            21 => 1,
            23 => 1,
            29 => 1,
            32 => 1,
            35 => 1,
            38 => 1,
            40 => 1,
            41 => 1,
        ], $this->sniffAndGetNumWarningsPerLine(__DIR__ . '/JavascriptOutputFunctionsSniffTest.js'));
    }
}
