<?php

namespace DocConverter\Document;

/**
 * Parsed document representation
 */
class Document {

    /** @var string $title */
    private $title;

    /** @var string $author */
    private $author;

    /** @var string $isbn */
    private $isbn;

    /** @var string $issn */
    private $issn;

    /** @var array $options */
    private $options = [
        'format' => ['A5', 'B5']
    ];

    /** @var string $citationStandard */
    private $citationStandard = "";

    /** @var \DocConverter\Document\Element\AbstractElement[] $elements */
    private $elements;

    /** @var \DocConverter\Bibliography\AbstractSource[] $bibliographySources */
    private $bibliographySources;

    /**
     * @param array $elements
     * @param array $bibliographySources
     */
    public function __construct(array $elements, array $bibliographySources = []) {
        $this->elements = $elements;
        $this->bibliographySources = $bibliographySources;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAuthor(): string {
        return $this->author;
    }

    /**
     * @return \DocConverter\Document\Element\AbstractElement[]
     */
    public function getElements(): array {
        return $this->elements;
    }

    /**
     * @return \DocConverter\Bibliography\AbstractSource[]
     */
    public function getBibliographySources(): array {
        return $this->bibliographySources;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $author
     * @return $this
     */
    public function setAuthor(string $author) {
        $this->author = $author;
        return $this;
    }

    /**
     * @param array $elements
     * @return $this
     */
    public function setElements(array $elements) {
        $this->elements = $elements;
        return $this;
    }

    /**
     * @param array $bibliographySource
     * @return $this
     */
    public function setBibliographySource(array $bibliographySource) {
        $this->bibliographySources = $bibliographySource;
        return $this;
    }

}
