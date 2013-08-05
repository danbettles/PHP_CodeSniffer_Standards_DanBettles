<?php
/**
 * DanBettles_Sniffs_Debug_PHPCommentedDebuggingSniff
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 */

/**
 * DanBettles_Sniffs_Debug_PHPCommentedDebuggingSniff
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 */
class DanBettles_Sniffs_Debug_PHPCommentedDebuggingSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * The regular expression that will be used to search for debugging in comments.
     * 
     * Compiled when the sniff is registered.
     * 
     * @var string
     */
    private $_regexp = '';

    /**
     * Returns an array of tokens this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $this->_compileRegexp();

        return array(T_COMMENT);
    }

    /**
     * Creates a list of PCRE patterns from the specified list of values.
     * 
     * @param array $values The values that should appear in the list.
     * 
     * @return string
     */
    private function _createPCREPatternList(array $values)
    {
        return implode('|', array_map('preg_quote', $values));
    }

    /**
     * Compiles the regular expression that will be used to search for debugging in comments.
     * 
     * @return void
     */
    private function _compileRegexp()
    {
        //In the context of this sniff, these are functions that can be called without any arguments
        $special_functions = array(
            'exit',
            'die',
        );

        //In the context of this sniff, these are functions that must be called with one or more arguments
        $functions = array_merge(
            array(
                'print_r',
                'var_dump',
                'print',
                'echo',
            ), 
            $special_functions
        );

        $function_list = $this->_createPCREPatternList($functions);

        $pattern_templates = array(
            '\b(%s)\s*\(',
            '\b(%s)\s*["\']',
            '\b(%s)\s*\$',
        );

        $patterns = array();

        foreach ($pattern_templates as $pattern_template) {
            $patterns[] = sprintf($pattern_template, $function_list);
        }

        //Add a pattern to deal with the special case of 'function' calls with no argument list at all
        $patterns[] = '\b(' . $this->_createPCREPatternList($special_functions) . ')\s*;';

        $this->_regexp = '/' . implode('|', $patterns) . '/';
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $aFileTokenRecord = $phpcsFile->getTokens();

        if (preg_match($this->_regexp, $aFileTokenRecord[$stackPtr]['content'])) {
            $phpcsFile->addWarning("Commented debugging found", $stackPtr);
        }
    }
}