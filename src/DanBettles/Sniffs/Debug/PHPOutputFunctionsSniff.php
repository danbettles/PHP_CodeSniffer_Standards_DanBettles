<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\Debug;

use DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP\SuspectTokensSniff;
use Override;

use const T_ECHO;
use const T_PRINT;

class PHPOutputFunctionsSniff extends SuspectTokensSniff
{
    #[Override]
    public function register(): array
    {
        $this->setTokens([T_PRINT, T_ECHO]);

        return parent::register();
    }
}
