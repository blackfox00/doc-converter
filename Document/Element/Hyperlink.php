<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DocConverter\Document\Element;

/**
 * Hyperlink element
 */
class Hyperlink extends RefElement {

    /** @var string $link */
    private $link;

    /**
     * @param int $refId
     * @param string $link
     */
    public function __construct(string $refId, string $link) {
        parent::__construct($refId);
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink(): string {
        return $this->link;
    }

    /**
     * @param string $link
     * @return $this
     */
    public function setLink($link) {
        $this->link = $link;
        return $this;
    }

}
