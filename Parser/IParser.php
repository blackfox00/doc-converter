<?php

namespace DocConverter\Parser;

/**
 * Document parser interface
 */
interface IParser {

    public function __construct(string $file);

    public function parse(): \DocConverter\Document\Document;

    public static function getCompatibleMimeTypes(): array;
}
