<?php

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\Debug;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class PHPCommentedDebuggingSniff implements Sniff
{
    /**
     * The regular expression that will be used to search for debugging in comments.
     *
     * Compiled when the sniff is registered.
     *
     * @var string
     */
    private $regExp = '';

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->compileRegexp();

        return [T_COMMENT];
    }

    /**
     * Creates a list of PCRE patterns from the specified list of values.
     *
     * @param array $values The values that should appear in the list.
     */
    private function createPCREPatternList(array $values): string
    {
        return implode('|', array_map('preg_quote', $values));
    }

    /**
     * Compiles the regular expression that will be used to search for debugging in comments.
     */
    private function compileRegexp(): void
    {
        // In the context of this sniff, these are functions that can be called without any arguments.
        $special_functions = [
            'exit',
            'die',
        ];

        // In the context of this sniff, these are functions that must be called with one or more arguments.
        $functions = array_merge([
            'print_r',
            'var_dump',
            'print',
            'echo',
        ], $special_functions);

        $function_list = $this->createPCREPatternList($functions);

        $pattern_templates = [
            '\b(%s)\s*\(',
            '\b(%s)\s*["\']',
            '\b(%s)\s*\$',
        ];

        $patterns = [];

        foreach ($pattern_templates as $pattern_template) {
            $patterns[] = sprintf($pattern_template, $function_list);
        }

        // Add a pattern to deal with the special case of 'function' calls with no argument list at all.
        $patterns[] = '\b(' . $this->createPCREPatternList($special_functions) . ')\s*;';

        $this->regExp = '/' . implode('|', $patterns) . '/';
    }

    /**
     * {@inheritDoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $aFileTokenRecord = $phpcsFile->getTokens();

        if (preg_match($this->regExp, $aFileTokenRecord[$stackPtr]['content']) > 0) {
            $phpcsFile->addWarning("Commented debugging found", $stackPtr, 'Found');
        }
    }
}
