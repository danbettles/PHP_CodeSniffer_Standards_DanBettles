<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP;

use Override;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * @phpstan-type TokenRecord array{content:string}
 */
abstract class ForbiddenTokensSniff implements Sniff
{
    /**
     * An array containing the IDs of forbidden tokens
     *
     * @var int[]
     */
    private array $forbiddenTokens = [];

    #[Override]
    public function register(): array
    {
        return $this->forbiddenTokens;
    }

    #[Override]
    public function process(
        File $phpcsFile,
        $stackPtr,
    ): void {
        $this->addMessage(
            $phpcsFile,
            $stackPtr,
            $phpcsFile->getTokens()[$stackPtr],
        );
    }

    /**
     * Sets the IDs of forbidden tokens
     *
     * Call before `register()` is executed
     *
     * @param int[] $ids
     */
    protected function setTokens(array $ids): self
    {
        $this->forbiddenTokens = $ids;

        return $this;
    }

    /**
     * Returns the IDs of forbidden tokens
     *
     * @return int[]
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
     * @phpstan-param TokenRecord $tokenRecord  The sniffed token
     */
    protected function addMessage(
        File $file,
        int $tokenNo,
        array $tokenRecord,
    ): void {
        $file->addError("Forbidden token `%s` found", $tokenNo, 'Found', [$tokenRecord['content']]);
    }
}
