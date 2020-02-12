<?php

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

abstract class ForbiddenTokensSniff implements Sniff
{
    /**
     * An array containing the IDs of forbidden tokens
     *
     * @var array
     */
    private $forbiddenTokens = [];

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        return $this->forbiddenTokens;
    }

    /**
     * {@inheritDoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $aFileTokenRecord = $phpcsFile->getTokens();
        $this->addMessage($phpcsFile, $stackPtr, $aFileTokenRecord[$stackPtr]);
    }

    /**
     * Sets the IDs of forbidden tokens
     *
     * Call before `register()` is executed
     */
    protected function setTokens(array $aToken): self
    {
        $this->forbiddenTokens = $aToken;
        return $this;
    }

    /**
     * Gets the IDs of forbidden tokens
     */
    protected function getTokens(): array
    {
        return $this->forbiddenTokens;
    }

    /**
     * Called to add a message to the stack when a forbidden token is encountered
     *
     * The offending token is required to be passed for convenience's sake
     *
     * @param File $file  The file containing the sniffed token
     * @param int $tokenNo  The position of the sniffed token on the stack
     * @param array $tokenRecord  The sniffed token
     */
    protected function addMessage(File $file, int $tokenNo, array $tokenRecord): void
    {
        $file->addError("Forbidden token `%s` found", $tokenNo, 'Found', [$tokenRecord['content']]);
    }
}
