<?php

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\Debug;

use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;

class PHPDebugFunctionsSniff extends ForbiddenFunctionsSniff
{
    /**
     * A list of forbidden functions with their alternatives.
     *
     * The value is NULL if no alternative exists (i.e. the function should just not be used).
     *
     * @var array
     */
    public $forbiddenFunctions = [
        'print_r' => null,
        'var_dump' => null,
    ];
}
