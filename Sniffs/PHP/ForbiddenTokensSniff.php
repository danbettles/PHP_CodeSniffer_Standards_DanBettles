<?php
/**
 * DanBettles_Sniffs_PHP_ForbiddenTokensSniff
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 */

/**
 * DanBettles_Sniffs_PHP_ForbiddenTokensSniff
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 */
class DanBettles_Sniffs_PHP_ForbiddenTokensSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @type string
     */
    const MESSAGE_CODE = 'Found';

    /**
     * An array containing the IDs of forbidden tokens
     * 
     * @var array
     */
    private $_aToken = array();

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return $this->_aToken;
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
        $this->addMessage($phpcsFile, $stackPtr, $aFileTokenRecord[$stackPtr]);
    }

    /**
     * Sets the IDs of forbidden tokens
     * 
     * Call before DanBettles_Sniffs_PHP_ForbiddenTokensSniff::register() is executed
     * 
     * @param array $aToken The IDs of forbidden tokens
     *
     * @return void
     */
    protected function setTokens(array $aToken)
    {
        $this->_aToken = $aToken;
    }

    /**
     * Gets the IDs of forbidden tokens
     * 
     * @return array
     */
    protected function getTokens()
    {
        return $this->_aToken;
    }

    /**
     * Called to add a message to the stack when a forbidden token is encountered
     * 
     * The offending token is required to be passed for convenience's sake
     * 
     * @param PHP_CodeSniffer_File $oFile       The file containing the sniffed token
     * @param int                  $tokenNo     The position of the sniffed token on the stack
     * @param array                $aTokenField The sniffed token
     *
     * @return void
     */
    protected function addMessage(PHP_CodeSniffer_File $oFile, $tokenNo, array $aTokenField)
    {
        $oFile->addError("Forbidden token `%s` found", $tokenNo, self::MESSAGE_CODE, array($aTokenField['content']));
    }
}