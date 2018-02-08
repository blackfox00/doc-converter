<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DocConverter\Document\Element;

/**
 * Base reference element
 */
abstract class RefElement extends AbstractElement {

    /** @var string $refId */
    private $refId;

    /**
     * @param string $refId
     */
    public function __construct(string $refId) {
        $this->refId = $refId;
    }

    /**
     * @return string
     */
    public function getRefId(): string {
        return $this->refId;
    }

    /**
     * @param string $refId
     * @return $this
     */
    public function setRefId(string $refId) {
        $this->refId = $refId;
        return $this;
    }

}
