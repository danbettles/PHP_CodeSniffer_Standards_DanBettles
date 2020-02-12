<?php

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;

class SuspectTokensSniff extends ForbiddenTokensSniff
{
    /**
     * {@inheritDoc}
     */
    protected function addMessage(File $file, int $tokenNo, array $tokenRecord): void
    {
        $file->addWarning("Suspect token `%s` found", $tokenNo, 'Found', [$tokenRecord['content']]);
    }
}
