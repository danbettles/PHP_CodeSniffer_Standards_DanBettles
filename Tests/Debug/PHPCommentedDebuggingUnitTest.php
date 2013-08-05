<?php
/**
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */

/**
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */
class DanBettles_Tests_Debug_PHPCommentedDebuggingUnitTest extends AbstractSniffUnitTest
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
            8 => 1,
            9 => 1,
            10 => 1,
            11 => 1,
            12 => 1,
            13 => 1,
            14 => 1,
            15 => 1,
            16 => 1,
            17 => 1,
            18 => 1,
            19 => 1,
            20 => 1,
            21 => 1,
            22 => 1,
            23 => 1,
            24 => 1,
            25 => 1,
            26 => 1,
            27 => 1,

            29 => 1,
            30 => 1,
            31 => 1,
            32 => 1,
            33 => 1,
            34 => 1,
            35 => 1,
            36 => 1,
            37 => 1,
            38 => 1,
            39 => 1,
            40 => 1,
            41 => 1,
            42 => 1,
            43 => 1,
            44 => 1,
            45 => 1,
            46 => 1,
            47 => 1,
        );
    }
}