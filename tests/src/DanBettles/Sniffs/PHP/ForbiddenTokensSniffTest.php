<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\Tests\DanBettles\Sniffs\PHP;

use DanBettles\PhpCodeSnifferStandard\Tests\TestCase;
use DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP\ForbiddenTokensSniff;
use ReflectionClass;

class ForbiddenTokensSniffTest extends TestCase
{
    public function testIsAbstract(): void
    {
        $class = new ReflectionClass(ForbiddenTokensSniff::class);

        $this->assertTrue($class->isAbstract());
    }
}
