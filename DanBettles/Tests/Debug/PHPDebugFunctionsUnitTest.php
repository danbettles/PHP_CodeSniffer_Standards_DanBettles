<?php
/**
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */

/**
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */
class DanBettles_Tests_Debug_PHPDebugFunctionsUnitTest extends AbstractSniffUnitTest
{
    /**
     * @see parent::getErrorList()
     */
    protected function getErrorList($fixtureFilename = '')
    {
        return array(
            2 => 1,
            3 => 1,
            7 => 1,
            8 => 1,
        );
    }

    /**
     * @see parent::getWarningList()
     */
    protected function getWarningList($fixtureFilename = '')
    {
        return array();
    }
}