<?php
/**
 * DanBettles_Sniffs_PHP_SuspectTokensSniff
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 */

/**
 * DanBettles_Sniffs_PHP_SuspectTokensSniff
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 */
class DanBettles_Sniffs_PHP_SuspectTokensSniff extends DanBettles_Sniffs_PHP_ForbiddenTokensSniff
{


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
        $oFile->addWarning("Suspect token `%s` found", $tokenNo, self::MESSAGE_CODE, array($aTokenField['content']));

    }//end addMessage()


}//end class
