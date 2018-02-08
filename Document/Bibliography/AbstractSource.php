<?php

namespace DocConverter\Document\Bibliography;

/**
 * Abstract source representation container
 */
abstract class AbstractSource {

    /** @var string $tag */
    private $tag;

    /** @var string $title */
    private $title;

    /** @var string $year */
    private $year;

    /** @var \DocConverter\Document\Bibliography\BibliographyAuthor[] $authors */
    private $authors;

    /**
     * @return string
     */
    public function getTag(): string {
        return $this->tag;
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
    public function getYear(): string {
        return $this->year;
    }

    /**
     * @return array
     */
    public function getAuthors(): array {
        return $this->authors;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function setTag(string $tag) {
        $this->tag = $tag;
        return $this;
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
     * @param string $year
     * @return $this
     */
    public function setYear(string $year) {
        $this->year = $year;
        return $this;
    }

    /**
     * @param array $authors
     * @return $this
     */
    public function setAuthors(array $authors) {
        $this->authors = $authors;
        return $this;
    }

    /**
     * @param \DocConverter\Document\Bibliography\BibliographyAuthor $author
     * @return $this
     */
    public function addAuthor(\DocConverter\Document\Bibliography\BibliographyAuthor $author) {
        $this->authors[] = $author;
        return $this;
    }

    /**
     * @return array
     */
    public function getOtherAuthors(): array {
        $otherAuthors = [];
        foreach ($this->authors as $author) {
            if (!$author->isMain()) {
                $otherAuthors[] = $author;
            }
        }
        return $otherAuthors;
    }

    /**
     * @return \DocConverter\Document\Bibliography\BibliographyAuthor
     */
    public function getMainAuthor(): \DocConverter\Document\Bibliography\BibliographyAuthor {
        foreach ($this->authors as $author) {
            if ($author->isMain()) {
                return $author;
            }
        }
        return reset($this->authors);
    }

}
