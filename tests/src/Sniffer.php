<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\Tests;

use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use RuntimeException;

use function array_reduce;
use function count;

/**
 * @phpstan-type MessageArray array<string,mixed>
 * @phpstan-type MessagesArray array<int,array<int,MessageArray[]>>
 * @phpstan-type MessageCountPerLine array<int,int>
 *
 * @todo Extract this?
 */
class Sniffer
{
    private const int MSG_TYPE_ERROR = 0;

    private const int MSG_TYPE_WARNING = 1;

    /**
     * N.B. Lazy-loaded
     */
    private LocalFile $localFile;

    private static function createLocalFile(
        string $sniffFilePath,
        string $fixtureFilePath,
    ): LocalFile {
        $config = new Config();

        $ruleset = new Ruleset($config);
        $ruleset->registerSniffs([$sniffFilePath], [], []);
        $ruleset->populateTokenListeners();

        return new LocalFile($fixtureFilePath, $ruleset, $config);
    }

    public function __construct(
        private string $sniffFilePath,
        private string $fixtureFilePath,
    ) {
    }

    private function getLocalFile(): LocalFile
    {
        if (!isset($this->localFile)) {
            $this->localFile = self::createLocalFile($this->sniffFilePath, $this->fixtureFilePath);
        }

        return $this->localFile;
    }

    public function process(): self
    {
        $this->getLocalFile()->process();

        return $this;
    }

    /**
     * @phpstan-return MessagesArray
     */
    public function getWarnings(): array
    {
        return $this->getLocalFile()->getWarnings();
    }

    /**
     * @phpstan-return MessagesArray
     */
    public function getErrors(): array
    {
        return $this->getLocalFile()->getErrors();
    }

    /**
     * @phpstan-return MessageCountPerLine
     * @throws RuntimeException If the message-type is invalid
     */
    private function getNumMessagesPerLine(int $messageType): array
    {
        $messagesPerLine = match ($messageType) {
            self::MSG_TYPE_ERROR => $this->getErrors(),
            self::MSG_TYPE_WARNING => $this->getWarnings(),
            default => throw new RuntimeException("The message type, `{$messageType}`, is invalid"),
        };

        $messageCountPerLine = [];

        foreach ($messagesPerLine as $lineNo => $messagesPerColumn) {
            // Remember: there could be multiple columns per line and multiple messages per column
            $messageCountPerLine[$lineNo] = array_reduce(
                $messagesPerColumn,
                fn (int $carry, array $messages): int => $carry + count($messages),
                initial: 0,
            );
        }

        return $messageCountPerLine;
    }

    /**
     * @phpstan-return MessageCountPerLine
     */
    public function getNumWarningsPerLine(): array
    {
        return $this->getNumMessagesPerLine(self::MSG_TYPE_WARNING);
    }

    /**
     * @phpstan-return MessageCountPerLine
     */
    public function getNumErrorsPerLine(): array
    {
        return $this->getNumMessagesPerLine(self::MSG_TYPE_ERROR);
    }
}
