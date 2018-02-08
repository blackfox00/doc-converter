<?php

namespace DocConverter\Document\Element;

/**
 * Math element
 */
class Math extends AbstractElement {

    const TYPE_NORMAL = 1;
    const TYPE_INLINE = 2;

    /** @var string $latexEquation */
    private $latexEquation = "";

    /** @var int $type */
    private $type;

    public function __construct(string $latexEquation = "", int $type = self::TYPE_NORMAL) {
        $this->type = $type;
        $this->latexEquation = $latexEquation;
    }

    public function getLatexEquation(): string {
        return $this->latexEquation;
    }

    /**
     * @param string $latexEquation
     * @return $this
     */
    public function setLatexEquation(string $latexEquation) {
        $this->latexEquation = $latexEquation;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType(int $type) {
        $this->type = $type;
        return $this;
    }

}
