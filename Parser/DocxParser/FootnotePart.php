<?php

namespace DocConverter\Parser\DocxParser;

use \DocConverter\Document\Element;

/**
 * Reads footnote xml part
 */
class FootnotePart extends CommonPart {

    /** @var string $xmlFile */
    protected $xmlFile = "word/footnotes.xml";

    /**
     * @return array
     */
    public function parse(): array {
        $footnotes = [];
        $footnotesNode = $this->xmlReader->getElements("*");
        foreach ($footnotesNode as $footnoteNode) {
            $nodes = $this->xmlReader->getElements("*", $footnoteNode);
            $id = $this->xmlReader->getAttribute("w:id", $footnoteNode);
            $type = $this->xmlReader->getAttribute("w:type", $footnoteNode);
            $parentElement = new Element\Footnote((int)$id);
            if ($id !== null && $type === null) { //when w:type is omitted, it is a normal referencable footnote
                foreach ($nodes as $node) {
                    switch ($node->nodeName) {
                        case "w:p":
                            $this->readParahraph($node, $parentElement);
                            break;
                        case "w:tbl":
                            $this->readTable($node, $parentElement);
                            break;
                    }
                }
                $footnotes[$id] = $parentElement;
            }
        }

        return $footnotes;
    }

}
