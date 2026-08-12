<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use ReflectionClass;
use RuntimeException;

trait HtmlFixture
{
    /**
     * Reads the expected HTML from data/<test class without Test>/<name>.html, next to the test.
     *
     * Fixtures are stored with one element per line for readability. Whitespace between the
     * elements is collapsed into the single space the formatters actually output, so the
     * comparison itself stays exact.
     */
    private function expectedHtml(string $name): string
    {
        $class = new ReflectionClass($this);
        $path = dirname((string) $class->getFileName())
            . '/data/' . preg_replace('/Test$/', '', $class->getShortName())
            . '/' . $name . '.html';

        if (!is_file($path)) {
            throw new RuntimeException('Missing HTML fixture ' . $path);
        }

        return (string) preg_replace('/\s+/', ' ', trim((string) file_get_contents($path)));
    }
}
