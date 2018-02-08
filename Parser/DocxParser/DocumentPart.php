<?php

namespace DocConverter\Parser\DocxParser;

use \DocConverter\Document\Element;

/**
 * Reads document xml part
 */
class DocumentPart extends CommonPart {

    /** @var string $xmlFile */
    protected $xmlFile = "word/document.xml";

    /**
     * @return type
     */
    public function parse(): array {
        $parentElement = new Element\Body();
        $section = new Element\Section();
        $parentElement->addChild($section);
        $unprocessedElements = [];
        $nodes = $this->xmlReader->getElements("w:body/*");
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                switch ($node->nodeName) {
                    case "w:p":
                        $this->readParahraph($node, $section);
                        $isEndOfSection = $this->xmlReader->elementExists("w:pPr/w:sectPr", $node);

                        if ($isEndOfSection) {
                            //TODO set THIS section style, check columsn, type (= nextpage}
                            $section->setColumns($this->xmlReader->getAttribute("w:num", $node, "w:pPr/w:sectPr/w:cols") ?: 1);
                            //assign new section to current section variable
                            $section = new Element\Section();
                            $parentElement->addChild($section);
                        }
                        break;
                    case "w:tbl":
                        $this->readTable($node, $section);
                        break;
                    case "w:sectPr": //last section properties
                        $lastSection = $parentElement->getChildByType("Section");
                        $lastSection->setColumns($this->xmlReader->getAttribute("w:num", $node, "w:cols") ?: 1);
                        break;
                    case "w:bookmarkStart": //ignore
                    case "w:bookmarkEnd": //ignore
                        break;
                    default: //any other TODO 
                        $unprocessedElements[] = $node->nodeName;
                        break;
                }
            }
        }

        return [$parentElement];
    }

}
