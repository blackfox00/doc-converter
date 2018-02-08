<?php

namespace DocConverter;

/**
 * Main library class & access point
 */
class DocConverter {

    private $files = [];
    private $documents = [];

    /**
     * @param array|string $files
     */
    public function __construct($files) {
        if (is_array($files)) {
            $this->files = $files;
        } else {
            $this->files[] = $files;
        }
    }

    /**
     * @param string $file
     */
    public function addFile($file) {
        $this->files[] = $file;
    }

    /**
     * @return \DocConverter\Document\Document[]
     */
    public function processFiles(): array {
        foreach ($this->files as $file) {
            $parser = Parser\ParserFactory::createParser($file);
            $this->documents[] = $parser->parse($file);
        }
        return $this->documents;
    }

}
