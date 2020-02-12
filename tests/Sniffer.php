<?php

namespace DanBettles\PhpCodeSnifferStandard\Tests;

use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use RuntimeException;

//@todo Extract this.
class Sniffer
{
    /** @var int */
    private const MSG_TYPE_ERROR = 0;

    /** @var int */
    private const MSG_TYPE_WARNING = 1;

    /** @var string */
    private $sniffFilePath;

    /** @var string */
    private $fixtureFilePath;

    /** @var LocalFile */
    private $localFile;

    public function __construct(string $sniffFilePath, string $fixtureFilePath)
    {
        $this
            ->setSniffFilePath($sniffFilePath)
            ->setFixtureFilePath($fixtureFilePath)
            ->setLocalFile($this->createLocalFile(
                $this->getSniffFilePath(),
                $this->getFixtureFilePath()
            ))
        ;
    }

    private function setSniffFilePath(string $filename): self
    {
        $this->sniffFilePath = $filename;
        return $this;
    }

    private function getSniffFilePath(): string
    {
        return $this->sniffFilePath;
    }

    private function setFixtureFilePath(string $filename): self
    {
        $this->fixtureFilePath = $filename;
        return $this;
    }

    private function getFixtureFilePath(): string
    {
        return $this->fixtureFilePath;
    }

    private function setLocalFile(LocalFile $localFile): self
    {
        $this->localFile = $localFile;
        return $this;
    }

    private function getLocalFile(): LocalFile
    {
        return $this->localFile;
    }

    private function createLocalFile(string $sniffFilePath, string $fixtureFilePath): LocalFile
    {
        $config = new Config();

        $ruleset = new Ruleset($config);
        $ruleset->registerSniffs([$sniffFilePath], [], []);
        $ruleset->populateTokenListeners();

        return new LocalFile($fixtureFilePath, $ruleset, $config);
    }

    public function process(): self
    {
        $this->getLocalFile()->process();
        return $this;
    }

    public function getWarnings(): array
    {
        return $this->getLocalFile()->getWarnings();
    }

    public function getErrors(): array
    {
        return $this->getLocalFile()->getErrors();
    }

    /**
     * @throws RuntimeException If the specified message-type is invalid.
     */
    private function getNumMessagesPerLine(int $messageType): array
    {
        $messages = [];

        switch ($messageType) {
            case self::MSG_TYPE_ERROR:
                $messages = $this->getLocalFile()->getErrors();
                break;

            case self::MSG_TYPE_WARNING:
                $messages = $this->getLocalFile()->getWarnings();
                break;

            default:
                throw new RuntimeException("The message type (`{$messageType}`) is invalid.");
        }

        $numMessagesPerLine = [];

        foreach ($messages as $lineNo => $messagesByColumn) {
            $numMessagesPerLine[$lineNo] = \count($messagesByColumn);
        }

        return $numMessagesPerLine;
    }

    public function getNumWarningsPerLine(): array
    {
        return $this->getNumMessagesPerLine(self::MSG_TYPE_WARNING);
    }

    public function getNumErrorsPerLine(): array
    {
        return $this->getNumMessagesPerLine(self::MSG_TYPE_ERROR);
    }
}
