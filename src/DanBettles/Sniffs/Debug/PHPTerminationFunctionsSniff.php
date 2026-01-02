<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\Debug;

use DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP\SuspectTokensSniff;
use Override;

use const T_EXIT;

class PHPTerminationFunctionsSniff extends SuspectTokensSniff
{
    #[Override]
    public function register(): array
    {
        $this->setTokens([T_EXIT]);

        return parent::register();
    }
}
