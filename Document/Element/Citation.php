<?php

namespace DocConverter\Document\Element;

/**
 * Citation element
 */
class Citation extends AbstractElement {

    /** @var string $referenceLabel */
    private $referenceLabel = "";

    /** @var int $page */
    private $page = 0;

    /** @var int $volume */
    private $volume = 0;

    /**
     * @param string $referenceLabel
     * @param int $page
     * @param int $volume
     */
    public function __construct(string $referenceLabel, int $page = 0, int $volume = 0) {
        $this->referenceLabel = $referenceLabel;
        $this->page = $page;
        $this->volume = $volume;
    }

    /**
     * @return string
     */
    public function getReferenceLabel(): string {
        return $this->referenceLabel;
    }

    /**
     * @return int
     */
    public function getPage(): int {
        return $this->page;
    }

    /**
     * @param string $referenceLabel
     * @return $this
     */
    public function setReferenceLabel(string $referenceLabel) {
        $this->referenceLabel = $referenceLabel;
        return $this;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setPage(int $page) {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getVolume(): int {
        return $this->volume;
    }

    /**
     * @param int $volume
     * @return $this
     */
    public function setVolume(int $volume) {
        $this->volume = $volume;
        return $this;
    }

}
