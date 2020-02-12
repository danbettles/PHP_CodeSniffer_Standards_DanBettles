<?php

namespace DanBettles\PhpCodeSnifferStandard\Tests\DanBettles\Sniffs\Debug;

use DanBettles\PhpCodeSnifferStandard\Tests\TestCase;

class PHPDebugFunctionsSniffTest extends TestCase
{
    public function testSniffEmitsErrors()
    {
        $this->assertEquals([
            2 => 1,
            3 => 1,
            7 => 1,
            8 => 1,
        ], $this->sniffAndGetNumErrorsPerLine(__DIR__ . '/PHPDebugFunctionsSniffTest.inc'));
    }
}
