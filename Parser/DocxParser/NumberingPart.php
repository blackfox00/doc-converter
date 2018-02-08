<?php

namespace DocConverter\Parser\DocxParser;

/**
 * Reads numbering xml part
 */
class NumberingPart extends CommonPart {

    /** @var string $xmlFile */
    protected $xmlFile = "word/numbering.xml";

    public function parse(): array {
        $numberings = [];
        $abstractNumberings = [];
        $numberingAbstractNodes = $this->xmlReader->getElements("w:abstractNum");
        foreach ($numberingAbstractNodes as $numberingAbstractNode) {
            $abstractNumId = (int) $this->xmlReader->getAttribute("w:abstractNumId", $numberingAbstractNode);

            $type = $this->xmlReader->getAttribute("w:val", $numberingAbstractNode, "w:lvl/w:numFmt");
            $numberingProperties = [
                'class' => $type === "bullet" ? \DocConverter\Document\Element\ListRoot::UNORDERED_LIST_CLASS : \DocConverter\Document\Element\ListRoot::ORDERED_LIST_CLASS
            ];
            $abstractNumberings[$abstractNumId] = $numberingProperties;
        }

        //remap
        $numberingNodes = $this->xmlReader->getElements("w:num");
        foreach ($numberingNodes as $numberingNode) {
            $numId = (int) $this->xmlReader->getAttribute("w:numId", $numberingNode);
            $abstractNumId = (int) $this->xmlReader->getAttribute("w:val", $numberingNode, "w:abstractNumId");
            $numberings[$numId] = $abstractNumberings[$abstractNumId];
        }

        return $numberings;
    }

}
