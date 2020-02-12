<?php

namespace DanBettles\PhpCodeSnifferStandard\Tests\DanBettles\Sniffs\Debug;

use DanBettles\PhpCodeSnifferStandard\Tests\TestCase;

class PHPTerminationFunctionsSniffTest extends TestCase
{
    public function testSniffEmitsWarnings()
    {
        $this->assertEquals([
            2 => 1,
            3 => 1,
            4 => 1,
            5 => 1,
            9 => 1,
            10 => 1,
        ], $this->sniffAndGetNumWarningsPerLine(__DIR__ . '/PHPTerminationFunctionsSniffTest.inc'));
    }
}
