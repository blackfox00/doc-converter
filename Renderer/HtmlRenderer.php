<?php

namespace DocConverter\Renderer;

use \DocConverter\Document\Element;

/**
 * Renders Document into HTML code
 */
class HtmlRenderer implements IRenderer {

    const PAGE_BREAK = "=================================== PAGE BREAK =====================================";

    /**
     * Render document into HTML string
     * @return string
     * @throws \DocConverter\Exception\InvalidStateException
     */
    public function render(\DocConverter\Document\Document $document): string {
        if (!$document) {
            throw new \DocConverter\Exception\InvalidStateException("Document to render must be set");
        }

        $elements = $document->getElements();
        //de($elements);
        $rendered = "";
        foreach ($elements as $element) {
            $rendered .= $this->renderElement($element);
        }
        return $rendered;
    }

    /**
     * Calls element rendering methods
     * @param \DocConverter\Document\Element\AbstractElement $element
     * @return string
     */
    public function renderElement(\DocConverter\Document\Element\AbstractElement $element): string {
        $result = "";
        foreach ($element->getChildren() as $el) {
            $class = \DocConverter\Utils::getPureClassName($el);
            $method = "render" . $class;
            if (!method_exists($this, "render" . $class)) {
                throw new \DocConverter\Exception\InvalidStateException(""); //TODO
            }
            $result .= $this->{$method}($el);
        }
        return $result;
    }

    /**
     * @param \DocConverter\Document\Element\Body $element
     * @return string
     */
    public function renderBody(\DocConverter\Document\Element\Body $element): string {
        return "<div>{$this->renderElement($element)}</div>";
    }

    /**
     * @param \DocConverter\Document\Element\Citation $element
     * @return string
     */
    public function renderCitation(\DocConverter\Document\Element\Citation $element): string {
        return "[<span data-citation=\"\">{$element->getReferenceLabel()}</span>]";
    }

    /**
     * @param \DocConverter\Document\Element\Endnote $element
     * @return string
     */
    public function renderEndnote(\DocConverter\Document\Element\Endnote $element): string {
        return "(<span data-endnote=\"" . $this->renderElement($element) . "\">" . $element->getRefId() . "</span>)";
    }

    /**
     * @param \DocConverter\Document\Element\Figure $element
     * @return string
     */
    public function renderFigure(\DocConverter\Document\Element\Figure $element): string {
        return "<figure>{$this->renderElement($element)}</figure>";
    }

    /**
     * @param \DocConverter\Document\Element\FigureCaption $element
     * @return string
     */
    public function renderFigureCaption(\DocConverter\Document\Element\FigureCaption $element): string {
        return "<figcaption>{$this->renderElement($element)}</figcaption>";
    }

    /**
     * @param \DocConverter\Document\Element\Footnote $element
     * @return string
     */
    public function renderFootnote(\DocConverter\Document\Element\Footnote $element): string {
        return "(<span data-footnote=\"" . $this->renderElement($element) . "\">" . $element->getRefId() . "</span>)";
    }

    /**
     * @param \DocConverter\Document\Element\Header $element
     * @return string
     */
    public function renderHeader(\DocConverter\Document\Element\Header $element): string {
        return "<h{$element->getDepth()}>{$this->renderElement($element)}</h{$element->getDepth()}>";
    }

    /**
     * @param \DocConverter\Document\Element\Hyperlink $element
     * @return string
     */
    public function renderHyperlink(\DocConverter\Document\Element\Hyperlink $element): string {
        return "<a href=\"{$element->getLink()}\">{$this->renderElement($element)}</a>";
    }

    /**
     * @param \DocConverter\Document\Element\Image $element
     * @return string
     */
    public function renderImage(\DocConverter\Document\Element\Image $element): string {
        return "<img src=\"data:{$element->getMimeType()};base64," . base64_encode($element->getImageData()) . "\">";
    }

    /**
     * @param \DocConverter\Document\Element\LineBreak $element
     * @return string
     */
    public function renderLineBreak(\DocConverter\Document\Element\LineBreak $element): string {
        return "<br>";
    }

    /**
     * @param \DocConverter\Document\Element\ListItem $element
     * @return string
     */
    public function renderListItem(\DocConverter\Document\Element\ListItem $element): string {
        return "<li>{$this->renderElement($element)}</li>";
    }

    /**
     * @param \DocConverter\Document\Element\Math $element
     * @return string
     */
    public function renderMath(\DocConverter\Document\Element\Math $element): string {
        return $element->getType() == Element\Math::TYPE_INLINE ? "\\(" . $element->getLatexEquation() . "\\)" : "\$\$" . $element->getLatexEquation() . "\$\$";
    }

    /**
     * @param \DocConverter\Document\Element\OrderedList $element
     * @return string
     */
    public function renderOrderedList(\DocConverter\Document\Element\OrderedList $element): string {
        return "<ol>{$this->renderElement($element)}</ol>";
    }

    /**
     * @param \DocConverter\Document\Element\PageBreak $element
     * @return string
     */
    public function renderPageBreak(\DocConverter\Document\Element\PageBreak $element): string {
        return self::PAGE_BREAK;
    }

    /**
     * @param \DocConverter\Document\Element\Paragraph $element
     * @return string
     */
    public function renderParagraph(\DocConverter\Document\Element\Paragraph $element): string {
        return "<p>{$this->renderElement($element)}</p>";
    }

    /**
     * @param \DocConverter\Document\Element\Section $element
     * @return string
     */
    public function renderSection(\DocConverter\Document\Element\Section $element): string {
        return $this->renderElement($element);
    }

    /**
     * @param \DocConverter\Document\Element\Table $element
     * @return string
     */
    public function renderTable(\DocConverter\Document\Element\Table $element): string {
        return "<table>{$this->renderElement($element)}</table>";
    }

    /**
     * @param \DocConverter\Document\Element\TableCaption $element
     * @return string
     */
    public function renderTableCaption(\DocConverter\Document\Element\TableCaption $element): string {
        return "<caption>{$this->renderElement($element)}</caption>";
    }

    /**
     * @param \DocConverter\Document\Element\TableCell $element
     * @return string
     */
    public function renderTableCell(\DocConverter\Document\Element\TableCell $element): string {
        return "<td>{$this->renderElement($element)}</td>";
    }

    /**
     * @param \DocConverter\Document\Element\TableRow $element
     * @return string
     */
    public function renderTableRow(\DocConverter\Document\Element\TableRow $element): string {
        return "<tr>{$this->renderElement($element)}</tr>";
    }

    /**
     * @param \DocConverter\Document\Element\Text $element
     * @return string
     */
    public function renderText(\DocConverter\Document\Element\Text $element): string {
        $style = $element->getHighestPriorityStyle();
        $text = $element->getText();
        if ($style) { //apply text style
            $stylesToApply = [
                "Bold" => "font-weight: bold",
                "Italics" => "font-style: italic",
                "Underline" => "text-decoration: underline",
                "Subscript" => "vertical-align: sub; font-size: smaller",
                "Superscript" => "vertical-align: sup; font-size: smaller",
                "SmallCaps" => "font-variant: small-caps"
            ];

            $css = [];
            foreach ($stylesToApply as $styleName => $styleCommand) {
                if ($style->{"is" . $styleName}()) {
                    $css[] = $styleCommand;
                }
            }

            if ($css) {
                $text = "<span style=\"" . implode("; ", $css) . "\">{$text}</span>";
            }
        }
        return $text;
    }

    /**
     * @param \DocConverter\Document\Element\UnorderedList $element
     * @return string
     */
    public function renderUnorderedList(\DocConverter\Document\Element\UnorderedList $element): string {
        return "<ul>{$this->renderElement($element)}</ul>";
    }

}
