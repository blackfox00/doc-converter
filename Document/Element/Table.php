<?php

namespace DocConverter\Document\Element;

/**
 * Table element
 */
class Table extends AbstractElement {

    /** @var int $columns */
    private $columns = 0;

    /**
     * @param int $columns
     */
    public function __construct(int $columns = 0) {
        $this->columns = $columns;
    }

    /**
     * @return int
     */
    public function getColumns(): int {
        return $this->columns;
    }

    /**
     * @param int $columns
     * @return $this
     */
    public function setColumns(int $columns = 0) {
        $this->columns = $columns;
        return $this;
    }

}
