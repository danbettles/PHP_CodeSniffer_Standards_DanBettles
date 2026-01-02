<?php declare(strict_types=1);

namespace DanBettles\PhpCodeSnifferStandard\Tests;

use LogicException;
use PHPUnit\Framework\TestCase as BaseTestCase;
use RuntimeException;

use function array_diff_key;
use function array_keys;
use function array_map;
use function get_class;
use function implode;
use function preg_match;
use function str_replace;

use const DIRECTORY_SEPARATOR;

/**
 * @phpstan-import-type MessageCountPerLine from Sniffer
 *
 * @phpstan-type ConfigArray array{projectDir:string,standardName:string}
 *
 * @todo Extract this?
 */
class TestCase extends BaseTestCase
{
    /**
     * @phpstan-var ConfigArray
     */
    private static array $config;

    /**
     * @phpstan-param ConfigArray $config
     * @throws LogicException If the config is incomplete
     */
    public static function configure(array $config): void
    {
        $missing = array_diff_key([
            'projectDir' => '',
            'standardName' => '',
        ], $config);

        if ($missing) {
            $listOfMissingElements = implode(
                ', ',
                array_map(fn (string $key): string => "`{$key}`", array_keys($missing)),
            );

            throw new LogicException('The config is incomplete.  Missing elements: ' . $listOfMissingElements);
        }

        self::$config = $config;
    }

    /**
     * @phpstan-return ConfigArray
     * @throws LogicException If the class has not been configured
     */
    private static function getConfig(): array
    {
        if (!isset(self::$config)) {
            throw new LogicException('The class has not been configured');
        }

        return self::$config;
    }

    /**
     * N.B. Lazy-loaded
     */
    private string $sniffClassFilePath;

    /**
     * @throws RuntimeException If the test file is incorrectly named
     */
    private function getSniffClassFilePath(): string
    {
        if (!isset($this->sniffClassFilePath)) {
            $config = self::getConfig();

            $standardName = $config['standardName'];
            $regex = "~(\\\\{$standardName}\\\\Sniffs\\\\(?:\\w+)\\\\[^\\\\]+)Test$~";

            $matches = [];
            $matched = (bool) preg_match($regex, get_class($this), $matches);

            if (!$matched) {
                throw new RuntimeException('The test file is incorrectly named.');
            }

            $projectDir = $config['projectDir'];
            $this->sniffClassFilePath = "{$projectDir}/src" . str_replace('\\', DIRECTORY_SEPARATOR, $matches[1]) . '.php';
        }

        return $this->sniffClassFilePath;
    }

    /**
     * @phpstan-return MessageCountPerLine
     */
    public function sniffAndGetNumWarningsPerLine(string $fixtureFilePath): array
    {
        return (new Sniffer($this->getSniffClassFilePath(), $fixtureFilePath))
            ->process()
            ->getNumWarningsPerLine()
        ;
    }

    /**
     * @phpstan-return MessageCountPerLine
     */
    public function sniffAndGetNumErrorsPerLine(string $fixtureFilePath): array
    {
        return (new Sniffer($this->getSniffClassFilePath(), $fixtureFilePath))
            ->process()
            ->getNumErrorsPerLine()
        ;
    }
}
