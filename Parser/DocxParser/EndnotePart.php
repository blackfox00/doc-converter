<?php

namespace DocConverter\Parser\DocxParser;

use \DocConverter\Document\Element;

/**
 * Reads endnote xml part
 */
class EndnotePart extends CommonPart {

    /** @var string $xmlFile */
    protected $xmlFile = "word/endnotes.xml";

    /**
     * @return array
     */
    public function parse(): array {
        $endnotes = [];
        $endnoteNodes = $this->xmlReader->getElements("*");
        foreach ($endnoteNodes as $endnoteNode) {
            $nodes = $this->xmlReader->getElements("*", $endnoteNode);
            $id = $this->xmlReader->getAttribute("w:id", $endnoteNode);
            $type = $this->xmlReader->getAttribute("w:type", $endnoteNode);
            $parentElement = new Element\Endnote((int) $id);
            if ($id !== null && $type === null) { //when w:type is omitted, it is a normal referencable endnote
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
                $endnotes[$id] = $parentElement;
            }
        }

        return $endnotes;
    }

}
