<?php

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\Debug;

use DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\PHP\SuspectTokensSniff;

class PHPOutputFunctionsSniff extends SuspectTokensSniff
{
    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->setTokens([T_PRINT, T_ECHO]);
        return parent::register();
    }
}
