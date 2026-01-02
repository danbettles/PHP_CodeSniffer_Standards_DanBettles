<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\Tests\DanBettles\Sniffs\PHP;

use DanBettles\PhpCodeSnifferStandard\Tests\TestCase;
use DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP\ForbiddenTokensSniff;
use DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP\SuspectTokensSniff;

use function is_subclass_of;

class SuspectTokensSniffTest extends TestCase
{
    public function testIsAForbiddentokenssniff(): void
    {
        // @phpstan-ignore function.alreadyNarrowedType, method.alreadyNarrowedType
        $this->assertTrue(is_subclass_of(SuspectTokensSniff::class, ForbiddenTokensSniff::class));
    }
}
