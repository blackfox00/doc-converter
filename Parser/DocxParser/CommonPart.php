<?php

namespace DocConverter\Parser\DocxParser;

use \DocConverter\Document\Element;
use \DocConverter\Document\Style;

/**
 * Common DocxParser reading methods
 */
abstract class CommonPart {

    /** @var string $xmlFile */
    protected $xmlFile;

    /** @var string $docxFile */
    protected $docxFile;

    /** @var \PhpOffice\Common\XmlReader */
    protected $xmlReader;

    /** @var \DocConverter\Parser\DocxParser */
    protected $parser;

    /** @var \ZipArchive */
    protected $zipArchive;

    /**
     * @param \DocConverter\Parser\DocxParser $parser
     */
    public function __construct(\DocConverter\Parser\DocxParser $parser) {
        $this->parser = $parser;
        $this->xmlReader = new \PhpOffice\Common\XMLReader();
        $this->xmlReader->getDomFromZip($parser->getFile(), $this->xmlFile);
        $this->zipArchive = new \ZipArchive();
        $this->zipArchive->open($parser->getFile());
        // TODO handle errors            
    }

    /** =========================== COMMON METHODS ============================= */

    /**
     * Get parental section
     * @param \DocConverter\Document\Element\AbstractElement $element
     * @return \DocConverter\Document\Element\AbstractElement
     */
    protected function getSection(Element\AbstractElement $element): Element\AbstractElement {
        while (!($element instanceof Element\Section)) {
            $element = $element->getParent();
        }
        return $element;
    }

    /**
     * Get body (top level element)
     * @param \DocConverter\Document\Element\AbstractElement $element
     * @return \DocConverter\Document\Element\AbstractElement
     */
    protected function getBody(Element\AbstractElement $element): Element\AbstractElement {
        while ($element->getParent() !== null) {
            $element = $element->getParent();
        }
        return $element;
    }

    /**
     * Get list item parent (list root) with correct depth level
     * @param \DocConverter\Document\Element\AbstractElement $element
     * @param int $numId
     * @param int $depth
     * @return \DocConverter\Document\Element\AbstractElement
     */
    protected function getListParent(Element\AbstractElement $element, int $numId, int $depth) {
        $listParent = null;
        foreach ($element->getChildren() as $child) {
            if ($child instanceof Element\ListRoot) {
                if ($child->getNumId() === $numId) {
                    if ($depth === $child->getDepth()) {
                        $listParent = $child;
                    } else {
                        $listParent = $this->getListParent($child, $numId, $depth);
                    }
                }
            }
        }
        if (!$listParent) {
            $class = isset($this->parser->getNumbering()[$numId]) ? $this->parser->getNumbering()[$numId]["class"] : Element\ListRoot::ORDERED_LIST_CLASS;
            $listParent = new $class($numId, $depth);
            $element->addChild($listParent);
        }
        return $listParent;
    }

    /**
     * Tries to find element to assign caption to 
     * @param \DocConverter\Document\Element\AbstractElement $element
     * @return \DocConverter\Document\Element\Image
     */
    protected function getCaptionParent(Element\AbstractElement $element) {
        $captionParent = $element;
        foreach ($element->getChildren() as $child) {
            if ($child instanceof Element\Table || $child instanceof Element\Figure) {
                $captionParent = $child;
            }
        }
        return $captionParent;
    }

    /**
     * Reads w:p element
     * @param \PhpOffice\Common\XmlReader $this->xmlReader
     * @param \DOMElement $contextNode
     * @param Element $parentElement
     */
    protected function readParahraph(\DOMElement $contextNode, Element\AbstractElement $parentElement) {
        $isHeader = false;
        $isCaption = false;

        //get paragraph style
        $style = null;
        if ($this->xmlReader->elementExists('w:pPr', $contextNode)) {
            $styleId = $this->xmlReader->getAttribute("w:val", $contextNode, "w:pPr/w:pStyle");
            if ($styleId) {
                if (isset($this->parser->getStyles()[$styleId])) {
                    $style = $this->parser->getStyles()[$styleId];
                    $isHeader = $style->isHeader();
                    $isCaption = $style->isCaption();
                }
            }
        }

        if ($isHeader) { //header
            $header = new Element\Header();
            if ($style) {
                $header->addStyle($style);
            }
            $nodes = $this->xmlReader->getElements("w:r", $contextNode);
            foreach ($nodes as $node) {
                $this->readRun($node, $header);
            }
            $parentElement->addChild($header);
        } elseif ($isCaption) {
            //find parent for caption
            $captionParent = $this->getCaptionParent($this->getSection($parentElement));
            if ($captionParent instanceof Element\Table) { //TODO fix
                $caption = new Element\TableCaption();
            } else {
                $caption = new Element\FigureCaption();
            }
            $nodes = $this->xmlReader->getElements("w:r", $contextNode);
            foreach ($nodes as $node) {
                $this->readRun($node, $caption);
            }
            $captionParent->addChild($caption, Element\AbstractElement::ELEMENT_PREPEND);
        } elseif ($this->xmlReader->elementExists("w:r/w:instrText", $contextNode)) {
            //TODO READ instrText fields
        } elseif ($this->xmlReader->elementExists("w:pPr/w:numPr", $contextNode)) { //list
            $numId = $this->xmlReader->getAttribute("w:val", $contextNode, "w:pPr/w:numPr/w:numId");
            $depth = $this->xmlReader->getAttribute("w:val", $contextNode, "w:pPr/w:numPr/w:ilvl");
            $listItem = new Element\ListItem($depth, $numId);
            if ($style) {
                $listItem->addStyle($style);
            }

            $nodes = $this->xmlReader->getElements("w:r", $contextNode);
            foreach ($nodes as $node) {
                $this->readRun($node, $listItem);
            }

            $listParentElement = $this->getListParent($parentElement, (int) $numId, (int) $depth);
            $listParentElement->addChild($listItem);
        } elseif ($this->xmlReader->elementExists("w:r/w:drawing", $contextNode)) {
            $relId = $this->xmlReader->getAttribute('r:embed', $contextNode, 'w:r/w:drawing/wp:inline/a:graphic/a:graphicData/pic:pic/pic:blipFill/a:blip');
            $figure = $this->getImage($relId);
            $parentElement->addChild($figure);
        } elseif ($this->xmlReader->elementExists("w:r/w:pict", $contextNode)) {
            $relId = $this->xmlReader->getAttribute('r:id', $contextNode, 'w:r/w:pict/v:shape/v:imagedata');
            $figure = $this->getImage($relId);
            $parentElement->addChild($figure);
        } elseif ($this->xmlReader->getAttribute("w:type", $contextNode, "w:r/w:br") == "page") {
            $pageBreak = new Element\PageBreak;
            $parentElement->addChild($pageBreak);
        } else { //simple w:p paragraph
            $paragraph = new Element\Paragraph();
            if ($style) {
                $paragraph->addStyle($style);
            }
            $nodes = $this->xmlReader->getElements("*", $contextNode);
            foreach ($nodes as $node) {
                switch ($node->nodeName) {
                    case "w:r":
                        $this->readRun($node, $paragraph);
                        break;
                    case "w:hyperlink":
                        $relId = $this->xmlReader->getAttribute("r:id", $node);
                        if ($relId) { //TODO handle anchor?
                            if (!isset($this->parser->getRels()[$relId])) {
                                throw new \DocConverter\Exception\InvalidStateException("Cannot find hyperlink {$relId} relationship ID reference in relationship XML");
                            }

                            $link = $this->parser->getRels()[$relId]["target"];
                            $hyperlink = new Element\Hyperlink($relId, $link);
                            $nodes = $this->xmlReader->getElements("w:r", $node);
                            foreach ($nodes as $node) {
                                $this->readRun($node, $hyperlink);
                            }
                            $paragraph->addChild($hyperlink);
                        }
                        break;

                    case "m:oMathPara":
                    case "m:oMath":
                        $this->readMath($node, $paragraph);
                        break;
                    case "w:sdt":
                        $this->readSdt($node, $paragraph);
                        break;
                }
            }

            //insert only if paragraph is not empty
            if ($paragraph->getChildrenCount()) {
                $parentElement->addChild($paragraph);
            }
        }
    }

    /**
     * @param int $relId
     * @return \DocConverter\Document\Element\Figure
     * @throws \DocConverter\Exception\InvalidStateException
     */
    protected function getImage(string $relId): \DocConverter\Document\Element\Figure {
        if (!isset($this->parser->getRels()[$relId])) {
            throw new \DocConverter\Exception\InvalidStateException("Cannot find image relationship ID reference in relationship XML");
        }
        $imagePath = $this->parser->getRels()[$relId]["target"];
        $imageData = "";

        if ($this->zipArchive->locateName($imagePath)) {
            $imageData = $this->zipArchive->getFromName($imagePath);
        }

        $figure = new Element\Figure();
        $image = new Element\Image($relId, $imagePath, $imageData);
        $figure->addChild($image);
        return $figure;
    }

    /**
     * Read w:r
     * @param \DOMElement $contextNode
     * @param \DocConverter\Document\Element\AbstractElement $parentElement
     */
    protected function readRun(\DOMElement $contextNode, Element\AbstractElement $parentElement) {
        //get text run style
        $style = null;
        if ($this->xmlReader->elementExists('w:rPr', $contextNode)) {
            if ($this->xmlReader->elementExists("w:rPr/w:rStyle", $contextNode)) {
                $styleId = $this->xmlReader->getAttribute("w:val", $contextNode, "w:rPr/w:rStyle");
                if ($styleId) {
                    if (isset($this->parser->getStyles()[$styleId])) {
                        $style = $this->parser->getStyles()[$styleId];
                    }
                }
            } else {
                $rprNodes = $this->xmlReader->getElements("w:rPr", $contextNode);
                if ($style) {
                    $properties = $style->getProperties();
                } else {
                    $properties = [
                        "type" => "",
                        "styleId" => "",
                        "name" => "",
                        "header" => false,
                        "headerDepth" => 0,
                        "caption" => false,
                        "italics" => false,
                        "emphasis" => false,
                        "bold" => false,
                        "underline" => false,
                        "superscript" => false,
                        "subscript" => false,
                        "smallCaps" => false,
                        "priority" => 2
                    ];
                }

                foreach ($rprNodes as $rprNode) { //TODO move to separate method
                    $properties["italics"] = $this->xmlReader->elementExists("w:i", $rprNode) ?: $properties["italics"];
                    $properties["bold"] = $this->xmlReader->elementExists("w:b", $rprNode) ?: $properties["bold"];
                    $properties["underline"] = $this->xmlReader->elementExists("w:u", $rprNode) ?: $properties["underline"];
                    $properties["smallCaps"] = $this->xmlReader->elementExists("w:smallCaps", $rprNode) ?: $properties["smallCaps"];
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
                $style = new Style\Style($properties);
            }
        }

        $nodes = $this->xmlReader->getElements("*", $contextNode);
        foreach ($nodes as $node) {
            switch ($node->nodeName) {
                case "w:t":
                    $text = new Element\Text($node->nodeValue);
                    if ($style) {
                        $text->addStyle($style);
                    }
                    $parentElement->addChild($text);
                    break;
                case "w:br":
                case "w:cr":
                    $parentElement->addChild(new Element\LineBreak());
                    break;
                case "w:footnoteReference":
                    $relId = $this->xmlReader->getAttribute("w:id", $node);
                    if (!isset($this->parser->getFootnotes()[$relId])) {
                        throw new \DocConverter\Exception\InvalidStateException("Cannot find footnote ID {$relId} reference in relationship XML");
                    }
                    $footnote = $this->parser->getFootnotes()[$relId];
                    $parentElement->addChild($footnote);
                    break;
                case "w:endnoteReference":
                    $relId = $this->xmlReader->getAttribute("w:id", $node);
                    if (!isset($this->parser->getEndnotes()[$relId])) {
                        throw new \DocConverter\Exception\InvalidStateException("Cannot find endnote ID {$relId} reference in relationship XML");
                    }
                    $footnote = $this->parser->getEndnotes()[$relId];
                    $parentElement->addChild($footnote);
                    break;
                case "w:object":
                    $relId = $this->xmlReader->getAttribute("r:id", $node, "v:shape/v:imagedata");
                    $figure = $this->getImage($relId);
                    $parentElement->addChild($figure);
            }
        }
    }

    /**
     * Read w:sdt Element for citation
     * @param \DOMElement $contextNode
     * @param \DocConverter\Document\Element\AbstractElement $parentElement
     */
    protected function readSdt(\DOMElement $contextNode, Element\AbstractElement $parentElement) {
        if ($this->xmlReader->elementExists("w:sdtPr/w:citation", $contextNode)) { //TODO maybe remove condition
            $node = $this->xmlReader->getElement("w:sdtContent/w:r/w:instrText", $contextNode);
            $parts = explode(" ", trim($node->nodeValue));
            $t = array_shift($parts);
            $referenceLabel = "";
            $page = 0;
            $volume = 0;
            if (strtoupper(trim($t)) === "CITATION") {
                $referenceLabel = array_shift($parts);
                if ($referenceLabel) {
                    $iterator = new \ArrayIterator($parts);
                    foreach ($iterator as $item) {
                        if ($item === "\\p") {
                            $iterator->next();
                            $page = $iterator->current();
                        } elseif ($item === "\\v") {
                            $iterator->next();
                            $volume = $iterator->current();
                        }
                    }
                }
                $citation = new Element\Citation($referenceLabel, $page, $volume);
                $parentElement->addChild($citation);
            }
        }
    }

    /**
     * Reads m:oMath or m:oMathPara elements
     * @param \PhpOffice\Common\XmlReader $this->xmlReader
     * @param \DOMElement $contextNode
     * @param \DocConverter\Document\Element\AbstractElement $parentElement 
     */
    protected function readMath(\DOMElement $contextNode, Element\AbstractElement $parentElement) {
        $oomlToLatex = new Math\OOMLToLatex($this->xmlReader, $contextNode);
        $latexEquation = $oomlToLatex->convert();
        $math = new Element\Math($latexEquation, $contextNode->nodeName === "m:oMathPara" ? Element\Math::TYPE_NORMAL : Element\Math::TYPE_INLINE);
        $parentElement->addChild($math);
    }

    /**
     * Reads r:tbl element
     * @param \PhpOffice\Common\XmlReader $this->xmlReader
     * @param \DOMElement $contextNode
     * @param \DocConverter\Document\Element\AbstractElement $parentElement 
     */
    protected function readTable(\DOMElement $contextNode, Element\AbstractElement $parentElement) {
        //get table style
        $style = null;
        if ($this->xmlReader->elementExists('w:tblPr', $contextNode)) {
            $styleId = $this->xmlReader->getAttribute("w:val", $contextNode, "w:tblPr/w:tblStyle");
            if ($styleId) {
                if (isset($this->parser->getStyles()[$styleId])) {
                    $style = $this->parser->getStyles()[$styleId];
                }
            }
        }

        $columns = $this->xmlReader->countElements("w:tblGrid/w:gridCol", $contextNode);

        $table = new Element\Table($columns);
        if ($style) {
            $table->addStyle($style);
        }
        $rows = $this->xmlReader->getElements("w:tr", $contextNode);
        foreach ($rows as $row) {
            $rowElement = new Element\TableRow();
            $cells = $this->xmlReader->getElements("w:tc", $row);
            foreach ($cells as $cell) {
                $cellElement = new Element\TableCell();
                $nodes = $this->xmlReader->getElements("w:p", $cell);
                foreach ($nodes as $node) {
                    $this->readParahraph($node, $cellElement);
                }
                $rowElement->addChild($cellElement);
            }
            $table->addChild($rowElement);
        }
        $parentElement->addChild($table);
    }

    public function __destruct() {
        $this->zipArchive->close();
    }

}
