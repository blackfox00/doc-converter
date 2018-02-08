<?php

namespace DocConverter\Document\Element;

/**
 * Text element
 */
class Text extends AbstractElement {

    /** @var string $text */
    private $text = "";

    /**
     * @param string $text
     */
    public function __construct(string $text = "") {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text) {
        $this->text = $text;
        return $this;
    }

}
