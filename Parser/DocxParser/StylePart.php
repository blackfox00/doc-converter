<?php

namespace DocConverter\Parser\DocxParser;

/**
 * Reads styles xml part
 */
class StylePart extends CommonPart {

    /** @var string $xmlFile */
    protected $xmlFile = "word/styles.xml";

    /**
     * @return array
     */
    public function parse(): array {
        $styles = [];
        $nodes = $this->xmlReader->getElements('w:style');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {

                //init style properties that we want to keep track of 
                $properties = [
                    "type" => "",
                    "styleId" => "",
                    "name" => "",
                    "header" => false,
                    "caption" => false,
                    "headerDepth" => 0,
                    "italics" => false,
                    "emphasis" => false,
                    "bold" => false,
                    "underline" => false,
                    "superscript" => false,
                    "subscript" => false,
                    "smallCaps" => false,
                    "priority" => 1
                ];

                $properties["type"] = $this->xmlReader->getAttribute('w:type', $node);
                $properties["styleId"] = $this->xmlReader->getAttribute('w:styleId', $node);
                $properties["name"] = $this->xmlReader->getAttribute('w:val', $node, 'w:name');
                $headingMatches = [];
                preg_match('/heading\s*(\d)/i', $properties["name"], $headingMatches);

                if (!empty($headingMatches)) {
                    $properties["header"] = true;
                    $properties["headerDepth"] = $headingMatches[1];
                }

                $captionMatches = [];
                preg_match('/caption/i', $properties["name"], $captionMatches);
                if (!empty($captionMatches)) {
                    $properties["caption"] = true;
                }

                preg_match('/emphasis/i', $properties["name"], $captionMatches);
                if (!empty($captionMatches)) {
                    $properties["emphasis"] = true;
                }

                $rprNode = $this->xmlReader->getElement("w:rPr", $node);
                if ($rprNode) {
                    $properties["italics"] = $this->xmlReader->elementExists("w:i", $rprNode);
                    $properties["bold"] = $this->xmlReader->elementExists("w:b", $rprNode);
                    $properties["underline"] = $this->xmlReader->elementExists("w:u", $rprNode);
                    $properties["smallCaps"] = $this->xmlReader->elementExists("w:smallCaps", $rprNode);
                    $subSuperScriptNode = $this->xmlReader->getElement("w:vertAlign", $rprNode);
                    if ($subSuperScriptNode) {
                        $subSuperScriptAttribute = $subSuperScriptNode->getAttribute("w:val");
                        if ($subSuperScriptAttribute === "superscript") {
                            $properties["superscript"] = true;
                        } else if ($subSuperScriptAttribute === "subscript") {
                            $properties["subscript"] = true;
                        }
                    }
                }

                $style = new \DocConverter\Document\Style\Style($properties);
                $styles[$properties["styleId"]] = $style;
            }
        }

        return $styles;
    }

}
