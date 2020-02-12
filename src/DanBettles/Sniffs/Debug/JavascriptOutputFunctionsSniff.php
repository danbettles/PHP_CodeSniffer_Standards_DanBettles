<?php

namespace DanBettles\PhpCodeSnifferStandard\DanBettles\Sniffs\Debug;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Sniffs for uses of `window.alert()`.
 *
 * Using `window.alert()` is considered suspect because it is often used for debugging.
 *
 * The sniff looks for "alert" and for "window.alert".
 *
 * @todo Create a generic sniff from this
 */
class JavascriptOutputFunctionsSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = ['JS'];

    /**
     * The names of the types of tokens we consider insignificant with regards to token pattern matching.
     *
     * @var array
     */
    private $insignificantTokenTypes = [
        T_WHITESPACE,
        T_COMMENT,
    ];

    /**
     * If we find any one of these sets of tokens behind the target function name in some code then we *may* need to add
     * a warning/error - only if the tokens following the target function name are also suspect will we actually add a
     * warning/error.
     *
     * @var array
     */
    private $suspectPrecedingTokenRecordSet = [[
            ['code' => T_OPEN_TAG],
        ], [
            ['code' => T_SEMICOLON],
        ], [
            ['code' => T_OPEN_CURLY_BRACKET],
        ], [
            ['code' => T_CLOSE_CURLY_BRACKET],
        ], [
            ['code' => T_OPEN_TAG],
            ['code' => T_STRING, 'content' => 'window'],
            ['code' => T_OBJECT_OPERATOR],
        ], [
            ['code' => T_SEMICOLON],
            ['code' => T_STRING, 'content' => 'window'],
            ['code' => T_OBJECT_OPERATOR],
        ], [
            ['code' => T_OPEN_CURLY_BRACKET],
            ['code' => T_STRING, 'content' => 'window'],
            ['code' => T_OBJECT_OPERATOR],
        ], [
            ['code' => T_CLOSE_CURLY_BRACKET],
            ['code' => T_STRING, 'content' => 'window'],
            ['code' => T_OBJECT_OPERATOR],
        ]];

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        return [T_STRING];
    }

    /**
     * {@inheritDoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $aFileTokenRecord = $phpcsFile->getTokens();

        if (
            $aFileTokenRecord[$stackPtr]['content'] === 'alert'
            && $this->matchBehind($stackPtr, $aFileTokenRecord) === true
            && $this->matchAhead($stackPtr, $aFileTokenRecord) === true
        ) {
            $phpcsFile->addWarning("Use of `window.alert()` found", $stackPtr, 'Found');
        }
    }

    /**
     * Returns TRUE if the 'other' token matches the reference token, or FALSE otherwise.
     *
     * @param array $aRefTokenField The reference token (the token the other must match).
     * @param array $aTokenField    The 'other' token.
     */
    private function tokensEqual(array $aRefTokenField, array $aTokenField): bool
    {
        if ($aTokenField['code'] !== $aRefTokenField['code']) {
            return false;
        }

        if (
            array_key_exists('content', $aRefTokenField) === true
            && $aTokenField['content'] !== $aRefTokenField['content']
        ) {
            return false;
        }

        return true;
    }

    /**
     * Returns TRUE if the tokens behind the target function name are suspect, or FALSE otherwise.
     *
     * @param int   $stackPtr         The position of the current token in the stack passed in $tokens.
     * @param array $aFileTokenRecord All tokens in the file being scanned.
     */
    private function matchBehind($stackPtr, array $aFileTokenRecord): bool
    {
        $aSignificantPrecedingTokenRecordSet = [];

        foreach ($this->suspectPrecedingTokenRecordSet as $aSuspectTokenRecord) {
            $numPrecedingTokens = count($aSuspectTokenRecord);

            // Cache the required number of tokens because we may be able to save some time later.
            if (array_key_exists($numPrecedingTokens, $aSignificantPrecedingTokenRecordSet) === false) {
                $aSignificantPrecedingTokenRecordSet[$numPrecedingTokens] = $this->pluckSignificantPrecedingTokens(
                    $aFileTokenRecord,
                    $stackPtr - 1,
                    $numPrecedingTokens
                );
            }

            $aSignificantPrecedingTokenRecord = $aSignificantPrecedingTokenRecordSet[$numPrecedingTokens];

            foreach ($aSuspectTokenRecord as $i => $aSuspectTokenField) {
                if ($this->tokensEqual($aSuspectTokenField, $aSignificantPrecedingTokenRecord[$i]) === false) {
                    // Move on to the next set of tokens.
                    continue 2;
                }
            }

            // If we got this far then we matched a complete set of tokens.
            return true;
        }

        return false;
    }

    /**
     * Returns TRUE if the tokens ahead of the target function name are suspect, or FALSE otherwise.
     *
     * @param int   $stackPtr         The position of the current token in the stack passed in $tokens.
     * @param array $aFileTokenRecord All tokens in the file being scanned.
     */
    private function matchAhead($stackPtr, array $aFileTokenRecord): bool
    {
        $aFollowingTokenRecord = array_slice($aFileTokenRecord, $stackPtr + 1, 2);

        if (empty($aFollowingTokenRecord) === true) {
            return false;
        }

        $aFilteredFollowingTokenRecord = $this->filterInsignificantTokens($aFollowingTokenRecord);

        if (empty($aFilteredFollowingTokenRecord) === true) {
            return false;
        }

        $aFirstFollowingTokenField = reset($aFilteredFollowingTokenRecord);

        return $aFirstFollowingTokenField['code'] === T_OPEN_PARENTHESIS;
    }

    /**
     * Returns the specified array of tokens after removing whitespace tokens.
     *
     * @param array $aTokenRecord The array to filter.
     */
    private function filterInsignificantTokens(array $aTokenRecord): array
    {
        $aFilteredTokenRecord = [];

        foreach ($aTokenRecord as $aTokenField) {
            if (in_array($aTokenField['code'], $this->insignificantTokenTypes) === false) {
                $aFilteredTokenRecord[] = $aTokenField;
            }
        }

        return $aFilteredTokenRecord;
    }

    /**
     * Plucks significant tokens from the specified array.
     *
     * @param array $aTokenRecord The array in which to look for significant tokens.
     * @param int   $startIndex   The index from which to (potentially) start plucking.
     * @param int   $numRequired  The number of significant tokens to pluck.
     */
    private function pluckSignificantPrecedingTokens(array $aTokenRecord, $startIndex, $numRequired): array
    {
        $aSignificantTokenRecord = [];

        $i = $startIndex;

        while ($i >= 0 && count($aSignificantTokenRecord) < $numRequired) {
            if (in_array($aTokenRecord[$i]['code'], $this->insignificantTokenTypes) === false) {
                $aSignificantTokenRecord[] = $aTokenRecord[$i];
            }

            $i -= 1;
        }

        return array_reverse($aSignificantTokenRecord);
    }
}
