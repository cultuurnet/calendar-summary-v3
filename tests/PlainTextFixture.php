<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use ReflectionClass;
use RuntimeException;

trait PlainTextFixture
{
    /**
     * Reads the expected plain text from data/<test class without Test>/<name>.txt, next to the
     * test.
     *
     * Unlike the HTML fixtures nothing is collapsed, since every space and line break is part of
     * what the formatters output. Line breaks are stored as \n and read back as PHP_EOL.
     *
     * Every fixture ends with a newline so that no editor can break one by adding the final newline
     * it expects. That last newline is not part of the expectation: an output that does end with a
     * line break has a trailing blank line in its fixture.
     */
    private function expectedText(string $name): string
    {
        $class = new ReflectionClass($this);
        $path = dirname((string) $class->getFileName())
            . '/data/' . preg_replace('/Test$/', '', $class->getShortName())
            . '/' . $name . '.txt';

        if (!is_file($path)) {
            throw new RuntimeException('Missing plain text fixture ' . $path);
        }

        $contents = (string) preg_replace('/\n\z/', '', (string) file_get_contents($path));

        return str_replace("\n", PHP_EOL, $contents);
    }
}
