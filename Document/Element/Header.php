<?php

namespace DocConverter\Document\Element;

/**
 * Header element
 */
class Header extends AbstractElement {

    public function getDepth() {
        foreach ($this->getStyles() as $style) {
            return $style->getHeaderDepth();
        }
        return 1;
    }

}
