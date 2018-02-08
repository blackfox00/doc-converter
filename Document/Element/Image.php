<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DocConverter\Document\Element;

/**
 * Image element
 */
class Image extends RefElement {

    /** @var string $imagePath */
    private $imagePath = "";

    /** @var string $imageData */
    private $imageData = "";

    /** @var string $mimeType */
    private $mimeType = "";

    /**
     * @param int $refId
     * @param string $imagePath
     */
    public function __construct(string $refId, string $imagePath = "", string $imageData = "") {
        parent::__construct($refId);
        $this->imagePath = $imagePath;
        $this->imageData = $imageData;
        $this->mimeType = $this->detectMimeType($this->imageData);
    }

    private function detectMimeType($imageData) { //TODO move to utils class
        $f = finfo_open();
        return finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
    }

    /**
     * @return string
     */
    public function getMimeType(): string {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getImagePath(): string {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     * @return $this
     */
    public function setImagePath(string $imagePath) {
        $this->imagePath = $imagePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageData(): string {
        return $this->imageData;
    }

    /**
     * @param string $imageData
     * @return $this
     */
    public function setImageData(string $imageData) {
        $this->imageData = $imageData;
        $this->setMimeType();
        return $this;
    }

}
