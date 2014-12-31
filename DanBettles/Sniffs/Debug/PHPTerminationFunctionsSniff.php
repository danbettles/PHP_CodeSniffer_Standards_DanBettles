<?php
/**
 * DanBettles_Sniffs_Debug_PHPTerminationFunctionsSniff
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 */

/**
 * DanBettles_Sniffs_Debug_PHPTerminationFunctionsSniff
 *
 * @category PHP
 * @package  PHP_CodeSniffer_Standards_DanBettles
 * @author   Dan Bettles <danbettles@yahoo.co.uk>
 * @license  https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/danbettles/PHP_CodeSniffer_Standards_DanBettles
 */
class DanBettles_Sniffs_Debug_PHPTerminationFunctionsSniff extends DanBettles_Sniffs_PHP_SuspectTokensSniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $this->setTokens(array(T_EXIT));
        return parent::register();

    }//end register()


}//end class
