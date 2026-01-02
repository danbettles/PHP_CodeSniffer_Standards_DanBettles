<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\Debug;

use Override;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;

use const null;

class PHPDebugFunctionsSniff extends ForbiddenFunctionsSniff
{
    /**
     * A list of forbidden functions with their alternatives.
     *
     * The value is `null` if no alternative exists (i.e. the function should just not be used).
     */
    #[Override]
    public $forbiddenFunctions = [
        'print_r' => null,
        'var_dump' => null,
    ];
}
