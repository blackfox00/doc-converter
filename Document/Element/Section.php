<?php

namespace DocConverter\Document\Element;

/**
 * Section element
 */
class Section extends AbstractElement {

    /** @var int */
    private $columns;

    /**
     * @param int $columns
     */
    public function __construct(int $columns = 1) {
        $this->columns = $columns;
    }

    /**
     * @return int
     */
    public function getColumns(): int {
        return $this->columns;
    }

    /**
     * 
     * @param int $columns
     * @return $this
     */
    public function setColumns(int $columns = 1) {
        $this->columns = $columns;
        return $this;
    }

}
