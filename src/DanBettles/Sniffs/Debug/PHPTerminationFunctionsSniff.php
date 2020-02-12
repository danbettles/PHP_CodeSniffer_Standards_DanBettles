<?php

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\Debug;

use DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP\SuspectTokensSniff;

class PHPTerminationFunctionsSniff extends SuspectTokensSniff
{
    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->setTokens([T_EXIT]);

        return parent::register();
    }
}
