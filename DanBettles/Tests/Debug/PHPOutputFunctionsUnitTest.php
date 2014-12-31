<?php
/**
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */

/**
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */
class DanBettles_Tests_Debug_PHPOutputFunctionsUnitTest extends AbstractSniffUnitTest
{
    /**
     * @see parent::getErrorList()
     */
    protected function getErrorList($fixtureFilename = '')
    {
        return array();
    }

    /**
     * @see parent::getWarningList()
     */
    protected function getWarningList($fixtureFilename = '')
    {
        return array(
            2 => 1,
            3 => 1,
            4 => 1,
            5 => 1,
            9 => 1,
            10 => 1,
        );
    }
}