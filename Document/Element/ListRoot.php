<?php

namespace DocConverter\Document\Element;

/**
 * Base list parent/root element
 */
abstract class ListRoot extends AbstractElement {

    const ORDERED_LIST_CLASS = "\DocConverter\Document\Element\OrderedList";
    const UNORDERED_LIST_CLASS = "\DocConverter\Document\Element\UnorderedList";

    /** @var int $depth */
    private $depth;

    /** @var int $numId */
    private $numId;

    /**
     * @param int $numId
     * @param int $depth
     * @throws \DocConverter\Exception\InvalidStateException
     */
    public function __construct(int $numId = null, int $depth = null) {
        if ($numId === null) {
            throw new \DocConverter\Exception\InvalidStateException("List numId must be set");
        }

        if ($depth === null) {
            throw new \DocConverter\Exception\InvalidStateException("List depth must be set");
        }

        $this->depth = $depth;
        $this->numId = $numId;
    }

    /**
     * @return int
     */
    public function getDepth(): int {
        return $this->depth;
    }

    /**
     * @param int $depth
     * @return $this
     */
    public function setDepth(int $depth) {
        $this->depth = $depth;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumId(): int {
        return $this->numId;
    }

    /**
     * @param int $numId
     * @return $this
     */
    public function setNumId(int $numId) {
        $this->numId = $numId;
        return $this;
    }

}
