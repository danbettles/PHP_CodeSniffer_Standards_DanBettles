<?php
/**
 * DanBettles_Sniffs_Debug_JavascriptOutputFunctionsSniff
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 */

/**
 * Sniffs for uses of `window.alert()`.
 *
 * Using `window.alert()` is considered suspect because it is often used for debugging.
 *
 * The sniff looks for "alert" and for "window.alert".
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 * @todo     Create a generic sniff from this
 */
class DanBettles_Sniffs_Debug_JavascriptOutputFunctionsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('JS');

    /**
     * The names of the types of tokens we consider insignificant with regards to token pattern matching.
     *
     * @var array
     */
    private $_aInsignificantTokenType = array(
                                         T_WHITESPACE,
                                         T_COMMENT,
                                        );

    /**
     * If we find any one of these sets of tokens behind the target function name in some code then we *may* need to add
     * a warning/error - only if the tokens following the target function name are also suspect will we actually add a
     * warning/error.
     *
     * @var array
     */
    private $_aSuspectPrecedingTokenRecordSet = array(
                                                 array(
                                                  array('code' => T_OPEN_TAG),
                                                 ),
                                                 array(
                                                  array('code' => T_SEMICOLON),
                                                 ),
                                                 array(
                                                  array('code' => T_OPEN_CURLY_BRACKET),
                                                 ),
                                                 array(
                                                  array('code' => T_CLOSE_CURLY_BRACKET),
                                                 ),
                                                 array(
                                                  array('code' => T_OPEN_TAG),
                                                  array(
                                                   'code'    => T_STRING,
                                                   'content' => 'window',
                                                  ),
                                                  array('code' => T_OBJECT_OPERATOR),
                                                 ),
                                                 array(
                                                  array('code' => T_SEMICOLON),
                                                  array(
                                                   'code'    => T_STRING,
                                                   'content' => 'window',
                                                  ),
                                                  array('code' => T_OBJECT_OPERATOR),
                                                 ),
                                                 array(
                                                  array('code' => T_OPEN_CURLY_BRACKET),
                                                  array(
                                                   'code'    => T_STRING,
                                                   'content' => 'window',
                                                  ),
                                                  array('code' => T_OBJECT_OPERATOR),
                                                 ),
                                                 array(
                                                  array('code' => T_CLOSE_CURLY_BRACKET),
                                                  array(
                                                   'code'    => T_STRING,
                                                   'content' => 'window',
                                                  ),
                                                  array('code' => T_OBJECT_OPERATOR),
                                                 ),
                                                );


    /**
     * Returns an array of tokens this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);

    }//end register()


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

        if ($aFileTokenRecord[$stackPtr]['content'] === 'alert'
            && $this->_matchBehind($stackPtr, $aFileTokenRecord) === true
            && $this->_matchAhead($stackPtr, $aFileTokenRecord) === true
        ) {
            $phpcsFile->addWarning("Use of `window.alert()` found", $stackPtr);
        }

    }//end process()


    /**
     * Returns TRUE if the 'other' token matches the reference token, or FALSE otherwise.
     *
     * @param array $aRefTokenField The reference token (the token the other must match).
     * @param array $aTokenField    The 'other' token.
     *
     * @return bool
     */
    private function _tokensEqual(array $aRefTokenField, array $aTokenField)
    {
        if ($aTokenField['code'] !== $aRefTokenField['code']) {
            return false;
        }

        if (array_key_exists('content', $aRefTokenField) === true
            && $aTokenField['content'] !== $aRefTokenField['content']
        ) {
            return false;
        }

        return true;

    }//end _tokensEqual()


    /**
     * Returns TRUE if the tokens behind the target function name are suspect, or FALSE otherwise.
     *
     * @param int   $stackPtr         The position of the current token in the stack passed in $tokens.
     * @param array $aFileTokenRecord All tokens in the file being scanned.
     *
     * @return bool
     */
    private function _matchBehind($stackPtr, array $aFileTokenRecord)
    {
        $aSignificantPrecedingTokenRecordSet = array();

        foreach ($this->_aSuspectPrecedingTokenRecordSet as $aSuspectTokenRecord) {
            $numPrecedingTokens = count($aSuspectTokenRecord);

            // Cache the required number of tokens because we may be able to save some time later.
            if (array_key_exists($numPrecedingTokens, $aSignificantPrecedingTokenRecordSet) === false) {
                $aSignificantPrecedingTokenRecordSet[$numPrecedingTokens] = $this->_pluckSignificantPrecedingTokens(
                    $aFileTokenRecord,
                    $stackPtr - 1,
                    $numPrecedingTokens
                );
            }

            $aSignificantPrecedingTokenRecord = $aSignificantPrecedingTokenRecordSet[$numPrecedingTokens];

            foreach ($aSuspectTokenRecord as $i => $aSuspectTokenField) {
                if ($this->_tokensEqual($aSuspectTokenField, $aSignificantPrecedingTokenRecord[$i]) === false) {
                    // Move on to the next set of tokens.
                    continue 2;
                }
            }

            // If we got this far then we matched a complete set of tokens.
            return true;
        }//end foreach

        return false;

    }//end _matchBehind()


    /**
     * Returns TRUE if the tokens ahead of the target function name are suspect, or FALSE otherwise.
     *
     * @param int   $stackPtr         The position of the current token in the stack passed in $tokens.
     * @param array $aFileTokenRecord All tokens in the file being scanned.
     *
     * @return bool
     */
    private function _matchAhead($stackPtr, array $aFileTokenRecord)
    {
        $aFollowingTokenRecord = array_slice($aFileTokenRecord, $stackPtr + 1, 2);

        if (empty($aFollowingTokenRecord) === true) {
            return false;
        }

        $aFilteredFollowingTokenRecord = $this->_filterInsignificantTokens($aFollowingTokenRecord);

        if (empty($aFilteredFollowingTokenRecord) === true) {
            return false;
        }

        $aFirstFollowingTokenField = reset($aFilteredFollowingTokenRecord);

        return $aFirstFollowingTokenField['code'] === T_OPEN_PARENTHESIS;

    }//end _matchAhead()


    /**
     * Returns the specified array of tokens after removing whitespace tokens.
     *
     * @param array $aTokenRecord The array to filter.
     *
     * @return array
     */
    private function _filterInsignificantTokens(array $aTokenRecord)
    {
        $aFilteredTokenRecord = array();

        foreach ($aTokenRecord as $aTokenField) {
            if (in_array($aTokenField['code'], $this->_aInsignificantTokenType) === false) {
                $aFilteredTokenRecord[] = $aTokenField;
            }
        }

        return $aFilteredTokenRecord;

    }//end _filterInsignificantTokens()


    /**
     * Plucks significant tokens from the specified array.
     *
     * @param array $aTokenRecord The array in which to look for significant tokens.
     * @param int   $startIndex   The index from which to (potentially) start plucking.
     * @param int   $numRequired  The number of significant tokens to pluck.
     *
     * @return array
     */
    private function _pluckSignificantPrecedingTokens(array $aTokenRecord, $startIndex, $numRequired)
    {
        $aSignificantTokenRecord = array();

        $i = $startIndex;

        while ($i >= 0 && count($aSignificantTokenRecord) < $numRequired) {
            if (in_array($aTokenRecord[$i]['code'], $this->_aInsignificantTokenType) === false) {
                $aSignificantTokenRecord[] = $aTokenRecord[$i];
            }

            $i -= 1;
        }

        return array_reverse($aSignificantTokenRecord);

    }//end _pluckSignificantPrecedingTokens()


}//end class
