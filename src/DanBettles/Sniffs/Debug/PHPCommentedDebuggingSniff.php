<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\Debug;

use Override;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

use function array_map;
use function array_merge;
use function implode;
use function preg_match;
use function preg_quote;
use function sprintf;

use const T_COMMENT;

class PHPCommentedDebuggingSniff implements Sniff
{
    /**
     * The regular expression that will be used to search for debugging in comments.
     *
     * Compiled when the sniff is registered.
     */
    private string $regex = '';

    #[Override]
    public function register(): array
    {
        $this->compileRegex();

        return [T_COMMENT];
    }

    /**
     * Creates a list of PCRE patterns from the specified list of values.
     *
     * @param string[] $values The values that should appear in the list.
     */
    private function createPCREPatternList(array $values): string
    {
        return implode('|', array_map(preg_quote(...), $values));
    }

    /**
     * Compiles the regular expression that will be used to search for debugging in comments.
     */
    private function compileRegex(): void
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

        $this->regex = '/' . implode('|', $patterns) . '/';
    }

    #[Override]
    public function process(File $phpcsFile, $stackPtr): void
    {
        $aFileTokenRecord = $phpcsFile->getTokens();

        if (preg_match($this->regex, $aFileTokenRecord[$stackPtr]['content']) > 0) {
            $phpcsFile->addWarning("Commented debugging found", $stackPtr, 'Found');
        }
    }
}
