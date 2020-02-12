<?php

namespace DanBettles\PhpCodeSnifferStandard\Tests\DanBettles\Sniffs\PHP;

use DanBettles\PhpCodeSnifferStandard\Tests\TestCase;
use DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP\ForbiddenTokensSniff;
use DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP\SuspectTokensSniff;

class SuspectTokensSniffTest extends TestCase
{
    public function testIsAForbiddentokenssniff()
    {
        $this->assertTrue(is_subclass_of(SuspectTokensSniff::class, ForbiddenTokensSniff::class));
    }
}
