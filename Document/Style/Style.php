<?php

namespace DocConverter\Document\Style;

/**
 * Document style
 */
class Style {

    use \Nette\SmartObject;

    /** @var string $type */
    private $type = "";

    /** @var int $priority */
    private $priority = 1;

    /** @var string $styleId */
    private $styleId;

    /** @var string $name */
    private $name = "";

    /** @var int $headerDepth */
    private $headerDepth = 1;

    /** @var bool $header */
    private $header = false;

    /** @var bool $caption */
    private $caption = false;

    /** @var bool $underline */
    private $underline = false;

    /** @var bool $italics */
    private $italics = false;

    /** @var bool $emphasis */
    private $emphasis = false;

    /** @var bool $bold */
    private $bold = false;

    /** @var bool $isSubscript */
    private $subscript = false;

    /** @var bool $superscript */
    private $superscript = false;

    /** @var bool $smallCaps */
    private $smallCaps = false;

    /**
     * @param array $properties
     */
    public function __construct(array $properties = []) {
        foreach ($properties as $propertyName => $propertyValue) { //TODO Add check property exists?
            $method = "set" . ucfirst($propertyName);
            $this->{$method}($propertyValue);
        }
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getStyleId(): string {
        return $this->styleId;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getHeaderDepth(): int {
        return $this->headerDepth;
    }

    /**
     * @return int
     */
    public function getPriority(): int {
        return $this->priority;
    }

    /**
     * @return array
     */
    public function getProperties(): array {
        $properties = [
            "type" => $this->type,
            "styleId" => $this->styleId,
            "name" => $this->name,
            "header" => $this->header,
            "headerDepth" => $this->headerDepth,
            "caption" => $this->caption,
            "italics" => $this->italics,
            "emphasis" => $this->emphasis,
            "bold" => $this->bold,
            "underline" => $this->underline,
            "superscript" => $this->superscript,
            "subscript" => $this->subscript,
            "smallCaps" => $this->smallCaps,
            "priority" => 2
        ];
        return $properties;
    }

    /**
     * @return bool
     */
    public function isSmallCaps(): bool {
        return $this->smallCaps;
    }

    /**
     * @return bool
     */
    public function isUnderline(): bool {
        return $this->underline;
    }

    /**
     * @return bool
     */
    public function isHeader(): bool {
        return $this->header;
    }

    /**
     * @return bool
     */
    public function isCaption(): bool {
        return $this->caption;
    }

    /**
     * @return bool
     */
    public function isItalics(): bool {
        return $this->italics;
    }

    /**
     * @return bool
     */
    public function isEmphasis(): bool {
        return $this->emphasis;
    }

    /**
     * @return bool
     */
    public function isBold(): bool {
        return $this->bold;
    }

    /**
     * @return bool
     */
    public function isSubscript(): bool {
        return $this->subscript;
    }

    /**
     * @return bool
     */
    public function isSuperscript(): bool {
        return $this->superscript;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $styleId
     * @return $this
     */
    public function setStyleId(string $styleId) {
        $this->styleId = $styleId;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @param int $headerDepth
     * @return $this
     */
    public function setHeaderDepth(int $headerDepth) {
        $this->headerDepth = $headerDepth;
        return $this;
    }

    /**
     * @param bool $header
     * @return $this
     */
    public function setHeader(bool $header) {
        $this->header = $header;
        return $this;
    }

    /**
     * @param bool $caption
     * @return $this
     */
    public function setCaption(bool $caption) {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param bool $underline
     * @return $this
     */
    public function setUnderline(bool $underline) {
        $this->underline = $underline;
        return $this;
    }

    /**
     * @param bool $italics
     * @return $this
     */
    public function setItalics(bool $italics) {
        $this->italics = $italics;
        return $this;
    }

    /**
     * @param bool $emphasis
     * @return $this
     */
    public function setEmphasis(bool $emphasis) {
        $this->emphasis = $emphasis;
        return $this;
    }

    /**
     * @param bool $bold
     * @return $this
     */
    public function setBold(bool $bold) {
        $this->bold = $bold;
        return $this;
    }

    /**
     * @param bool $subscript
     * @return $this
     */
    public function setSubscript(bool $subscript) {
        $this->subscript = $subscript;
        return $this;
    }

    /**
     * @param bool $superscript
     * @return $this
     */
    public function setSuperscript(bool $superscript) {
        $this->superscript = $superscript;
        return $this;
    }

    /**
     * @param bool $smallCaps
     * @return $this
     */
    public function setSmallCaps(bool $smallCaps) {
        $this->smallCaps = $smallCaps;
        return $this;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority(int $priority) {
        $this->priority = $priority;
        return $this;
    }

}
