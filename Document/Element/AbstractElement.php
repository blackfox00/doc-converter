<?php

namespace DocConverter\Document\Element;

/**
 * Base of all document elements
 */
abstract class AbstractElement {

    use \Nette\SmartObject;

    /** @var \DocConverter\Document\Element\AbstractElement[] $children; */
    private $children = [];

    /** @var \DocConverter\Document\Style\Style[] $styles */
    private $styles = [];

    /** @var \DocConverter\Document\Element\AbstractElement */
    private $parent = null;

    const ELEMENT_APPEND = 1;
    const ELEMENT_PREPEND = 2;

    /**
     * @param \DocConverter\Document\Element\AbstractElement $element
     * @param int $method Prepend/Append the element (Append is default)
     * @return $this
     */
    public function addChild(AbstractElement $element, $method = self::ELEMENT_APPEND) {
        $element->setParent($this);
        if ($method === self::ELEMENT_PREPEND) {
            array_unshift($this->children, $element);
        } else {
            $this->children[] = $element;
        }
        return $this;
    }

    /**
     *  @return \DocConverter\Document\Style\Style
     */
    public function getHighestPriorityStyle() {
        $highestPriorityStyle = null;
        foreach ($this->getStyles() as $style) {
            if ($highestPriorityStyle === null) {
                $highestPriorityStyle = $style;
            }

            if ($style->getPriority() >= $highestPriorityStyle->getPriority()) {
                $highestPriorityStyle = $style;
            }
        }
        return $highestPriorityStyle;
    }

    /**
     * @param \DocConverter\Document\Style\Style $style
     */
    public function addStyle(\DocConverter\Document\Style\Style $style) {
        $this->styles[] = $style;
    }

    /**
     * @param \DocConverter\Document\Element\AbstractElement $element
     */
    public function setParent(AbstractElement $element) {
        $this->parent = $element;
    }

    /**
     * Return all element child elements
     * @return \DocConverter\Document\Element\AbstractElement[]
     */
    public function getChildren(): array {
        return $this->children;
    }

    /**
     * Return count of all child elements 
     * @return int
     */
    public function getChildrenCount(): int {
        return count($this->children);
    }

    /**
     * @param int $index
     * @return \DocConverter\Document\Element\AbstractElement
     * @throws \DocConverter\Exception\OutOfRangeException
     */
    public function getChildByIndex($index) {
        if (!isset($this->children[$index])) {
            throw new \DocConverter\Exception\OutOfRangeException("Invalid element children index");
        }
        return $this->children[$index];
    }

    /**
     * Return child by class type from the beginning or the end of the array
     * @param string $class
     * @param boolean $first
     * @return AbstractElement|null
     */
    public function getChildByType($class, $first = false) {
        $class = "\\DocConverter\\Document\\Element\\{$class}";
        $candidates = array_filter($this->children, function($child) use ($class) {
            return $child instanceof $class;
        });
        return $first ? array_shift($candidates) : array_pop($candidates);
    }

    /**
     * @return \DocConverter\Document\Style\Style[]
     */
    public function getStyles(): array {
        return $this->styles;
    }

    /**
     * @return \DocConverter\Document\Element\AbstractElement
     */
    public function getParent() {
        return $this->parent;
    }

}
