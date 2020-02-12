<?php

namespace DanBettles\PhpCodeSnifferStandard\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use RuntimeException;

//@todo Extract this.
class TestCase extends BaseTestCase
{
    /** @var string */
    private $projectDir;

    /** @var string */
    private $standardName;

    /** @var string */
    private $sniffClassFilePath;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        //@todo Deal with this.
        //###> Config ###
        $this->projectDir = \dirname(__DIR__);
        $this->standardName = 'DanBettles';
        //###< Config ###

        $pattern = "~(\\\\{$this->standardName}\\\\Sniffs\\\\(?:\\w+)\\\\[^\\\\]+)Test$~";
        $matches = [];
        $matched = (bool) \preg_match($pattern, \get_class($this), $matches);

        if (!$matched) {
            throw new RuntimeException('The test file is incorrectly named.');
        }

        $this->setSniffClassFilePath(
            "{$this->projectDir}/src" . \str_replace('\\', DIRECTORY_SEPARATOR, $matches[1]) . '.php'
        );

        parent::__construct($name, $data, $dataName);
    }

    private function setSniffClassFilePath(string $filename): self
    {
        $this->sniffClassFilePath = $filename;
        return $this;
    }

    public function getSniffClassFilePath(): string
    {
        return $this->sniffClassFilePath;
    }

    public function sniffAndGetNumWarningsPerLine(string $fixtureFilePath): array
    {
        return (new Sniffer($this->getSniffClassFilePath(), $fixtureFilePath))
            ->process()
            ->getNumWarningsPerLine()
        ;
    }

    public function sniffAndGetNumErrorsPerLine(string $fixtureFilePath): array
    {
        return (new Sniffer($this->getSniffClassFilePath(), $fixtureFilePath))
            ->process()
            ->getNumErrorsPerLine()
        ;
    }
}
