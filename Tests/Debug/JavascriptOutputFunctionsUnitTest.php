<?php
/**
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */

/**
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */
class DanBettles_Tests_Debug_JavascriptOutputFunctionsUnitTest extends AbstractSniffUnitTest
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
            1 => 1,
            6 => 1,
            7 => 1,
            11 => 1,
            15 => 1,
            21 => 1,
            23 => 1,
            29 => 1,
            32 => 1,
            35 => 1,
            38 => 1,
            40 => 1,
            41 => 1,
        );
    }
}