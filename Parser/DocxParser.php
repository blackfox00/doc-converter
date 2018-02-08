<?php

/**
 * This file is part of DocConverter application
 */

namespace DocConverter\Parser;

/**
 * Parser for Word 2007/2013 docx files
 */
class DocxParser implements \DocConverter\Parser\IParser {

    private static $compatibleMimeTypes = ["application/vnd.openxmlformats-officedocument.wordprocessingml.document"];

    /** @var string $docxFile */
    private $file = null;

    /** @var array $rels */
    private $rels = [];

    /** @var array $numbering */
    private $numbering = [];

    /** @var \DocConverter\Document\Style\Style[] $styles */
    private $styles = [];

    /** @var \DocConverter\Document\Element\AbstractElement[] $footNotes */
    private $footnotes = [];

    /** @var \DocConverter\Document\Element\AbstractElement[] $endNotes */
    private $endnotes = [];

    /** @var \DocConverter\Document\Element\AbstractElement[] $elements */
    private $elements = [];

    /** @var \DocConverter\Bibliography\AbstractSource[] $bibliographySources */
    private $bibliographySources = [];

    /**
     * @param string $file
     */
    public function __construct(string $file) {
        $this->file = $file;
        if (!$this->file) {
            throw new \DocConverter\Exception\InvalidStateException("You have to set file to parse!");
        }
    }

    /**
     * @return \DocConverter\Document\Document
     * @throws \DocConverter\Exception\InvalidStateException
     */
    public function parse(): \DocConverter\Document\Document {
        $this->rels = (new \DocConverter\Parser\DocxParser\RelsPart($this))->parse();
        $this->numbering = (new \DocConverter\Parser\DocxParser\NumberingPart($this))->parse();
        $this->bibliographySources = (new \DocConverter\Parser\DocxParser\BibliographyPart($this))->parse();
        $this->styles = (new \DocConverter\Parser\DocxParser\StylePart($this))->parse();
        $this->footnotes = (new \DocConverter\Parser\DocxParser\FootnotePart($this))->parse();
        $this->endnotes = (new \DocConverter\Parser\DocxParser\EndnotePart($this))->parse();
        $this->elements = (new \DocConverter\Parser\DocxParser\DocumentPart($this))->parse();
        return new \DocConverter\Document\Document($this->elements, $this->bibliographySources);
    }

    /**
     * @return string 
     */
    public function getFile(): string {
        return $this->file;
    }

    /**
     * @return \DocConverter\Document\Element\AbstractElement[]
     */
    public function getElements(): array {
        return $this->elements;
    }

    /**
     * @return array
     */
    public function getRels(): array {
        return $this->rels;
    }

    /**
     * @return array
     */
    public function getNumbering(): array {
        return $this->numbering;
    }

    /**
     * @return \DocConverter\Document\Style\Style[]
     */
    public function getStyles(): array {
        return $this->styles;
    }

    /**
     * @return \DocConverter\Document\Element\AbstractElement[]
     */
    public function getFootnotes(): array {
        return $this->footnotes;
    }

    /**
     * @return \DocConverter\Document\Element\AbstractElement[]
     */
    public function getEndnotes(): array {
        return $this->endnotes;
    }

    /**
     * @return array
     */
    public function getBibliography(): array {
        return $this->bibliography;
    }

    /**
     * @return array
     */
    public static function getCompatibleMimeTypes(): array {
        return self::$compatibleMimeTypes;
    }

}
