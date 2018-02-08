<?php

namespace DocConverter\Parser;

/**
 * Factory to create document parsers
 */
class ParserFactory {

    private static $availableParsers = [
        "\\DocConverter\\Parser\\DocxParser"
    ];

    /**
     * Creates parser by type (name)
     * @param string $file
     * @param string $name
     * @return \DocConverter\Parser\IParser
     * @throws Exception
     */
    public static function createParser(string $file) {
        $mimeType = mime_content_type($file);
        foreach (self::$availableParsers as $class) {
            if (class_exists($class) && self::isConcreteClass($class) && in_array(mime_content_type($file), $class::getCompatibleMimeTypes())) {
                return new $class($file);
            }
        }
        throw new \Exception("No parser found for file {$file} ({$mimeType})");
    }

    /**
     * @param string $class
     * @return boolean
     */
    private static function isConcreteClass($class): bool {
        $reflection = new \ReflectionClass($class);
        return !$reflection->isAbstract() && !$reflection->isInterface();
    }

}
