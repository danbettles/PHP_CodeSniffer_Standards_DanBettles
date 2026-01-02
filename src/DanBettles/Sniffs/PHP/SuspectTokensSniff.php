<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP;

use Override;
use PHP_CodeSniffer\Files\File;

class SuspectTokensSniff extends ForbiddenTokensSniff
{
    #[Override]
    protected function addMessage(
        File $file,
        int $tokenNo,
        array $tokenRecord,
    ): void {
        $file->addWarning("Suspect token `%s` found", $tokenNo, 'Found', [$tokenRecord['content']]);
    }
}
