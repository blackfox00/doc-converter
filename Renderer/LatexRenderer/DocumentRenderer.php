<?php

namespace DocConverter\Renderer\LatexRenderer;

use \DocConverter\Document\Element;

/**
 * LaTeX renderer of main document part
 */
class DocumentRenderer {

    private $headings = [
        1 => "section",
        2 => "subsection",
        3 => "subsubsection",
        4 => "paragraph",
        5 => "subparagraph",
        6 => "subparagraph",
    ];

    public function renderDocument(\DocConverter\Document\Document $document): string {
        $elements = $document->getElements();
        $rendered = "";
        foreach ($elements as $element) {
            //element type here is 'Body'
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
        $elementChildren = $element->getChildren();
        $index = 0;
        foreach ($elementChildren as $el) {
            $class = \DocConverter\Utils::getPureClassName($el);
            $method = "render" . $class;
            if (!method_exists($this, "render" . $class)) {
                throw new \DocConverter\Exception\InvalidStateException("");
            }
            if ($element instanceof Element\Paragraph) {
                $nextElement = $children[$index + 1] ?? null;
                $result .= $this->{$method}($el, $nextElement);
            } else {
                $result .= $this->{$method}($el);
            }
            $index++;
        }
        return $result;
    }

    public function renderBody(\DocConverter\Document\Element\Citation $element): string {
        return "\\begin{document}\n{$this->renderElement($element)}\\end{document}\n";
    }

    /**
     * @param \DocConverter\Document\Element\Citation $element
     * @return string
     */
    public function renderCitation(\DocConverter\Document\Element\Citation $element): string {
        return "\\cite{{$element->getReferenceLabel()}}";
    }

    /**
     * @param \DocConverter\Document\Element\Endnote $element
     * @return string
     */
    public function renderEndnote(\DocConverter\Document\Element\Endnote $element): string {
        return "\\endnote{{$this->renderElement($element)}}";
    }

    /**
     * @param \DocConverter\Document\Element\Figure $element
     * @return string
     */
    public function renderFigure(\DocConverter\Document\Element\Figure $element): string {
        return "\\begin{figure}[H]\n{$this->renderElement($element)}\\end{figure}\n";
    }

    /**
     * @param \DocConverter\Document\Element\FigureCaption $element
     * @return string
     */
    public function renderFigureCaption(\DocConverter\Document\Element\FigureCaption $element): string {
        return "\\caption{{$this->renderElement($element)}}\n";
    }

    /**
     * @param \DocConverter\Document\Element\Footnote $element
     * @return string
     */
    public function renderFootnote(\DocConverter\Document\Element\Footnote $element): string {
        return "\\footnote{{$this->renderElement($element)}}";
    }

    /**
     * @param \DocConverter\Document\Element\Header $element
     * @return string
     */
    public function renderHeader(\DocConverter\Document\Element\Header $element): string {
        $heading = $this->headings[$element->getDepth()];
        return "\n\\{$heading}{{$this->renderElement($element)}}\n";
    }

    /**
     * @param \DocConverter\Document\Element\Hyperlink $element
     * @return string
     */
    public function renderHyperlink(\DocConverter\Document\Element\Hyperlink $element): string {
        return "\\href{{$element->getLink()}}{{$this->renderElement($element)}}";
    }

    /**
     * @param \DocConverter\Document\Element\Image $element
     * @return string
     */
    public function renderImage(\DocConverter\Document\Element\Image $element): string {
        return "\\includegraphics{{$element->getImagePath()}}\n";
    }

    /**
     * @param \DocConverter\Document\Element\LineBreak $element
     * @return string
     */
    public function renderLineBreak(\DocConverter\Document\Element\LineBreak $element): string {
        return " \\\\ ";
    }

    /**
     * @param \DocConverter\Document\Element\ListItem $element
     * @return string
     */
    public function renderListItem(\DocConverter\Document\Element\ListItem $element): string {
        return "\\item {$this->renderElement($element)}\n";
    }

    /**
     * @param \DocConverter\Document\Element\Math $element
     * @return string
     */
    public function renderMath(\DocConverter\Document\Element\Math $element): string {
        return $element->getType() == Element\Math::TYPE_INLINE ? "\\begin{math}" . $element->getLatexEquation() . "\\end{math}" : "\\begin{equation}\n" . $element->getLatexEquation() . "\n\\end{equation}\n";
    }

    /**
     * @param \DocConverter\Document\Element\OrderedList $element
     * @return string
     */
    public function renderOrderedList(\DocConverter\Document\Element\OrderedList $element): string {
        return "\\begin{itemize}\n{$this->renderElement($element)}\\end{itemize}\n";
    }

    /**
     * @param \DocConverter\Document\Element\PageBreak $element
     * @return string
     */
    public function renderPageBreak(\DocConverter\Document\Element\PageBreak $element): string {
        return "\n\\pagebreak\n\n";
    }

    /**
     * @param \DocConverter\Document\Element\Paragraph $element
     * @param \DocConverter\Document\Element\AbstractElement $nextElement
     * @return string
     */
    public function renderParagraph(\DocConverter\Document\Element\Paragraph $element, $nextElement = null): string {
        $return = "{$this->renderElement($element)}";
        if (!($element->getParent() instanceof Element\Footnote || $element->getParent() instanceof Element\Endnote)) {
            $return .= "\n";
        }
        return $return;
    }

    /**
     * @param \DocConverter\Document\Element\Section $element
     * @return string
     */
    public function renderSection(\DocConverter\Document\Element\Section $element): string {
        return "";
    }

    /**
     * @param \DocConverter\Document\Element\Table $element
     * @return string
     */
    public function renderTable(\DocConverter\Document\Element\Table $element): string {
        $columnDefinition = "|" . str_repeat("c|", $element->getColumns());
        return "\\begin{tabular}{{$columnDefinition}}\n{$this->renderElement($element)}\\end{tabular}\n";
    }

    /**
     * @param \DocConverter\Document\Element\TableCaption $element
     * @return string
     */
    public function renderTableCaption(\DocConverter\Document\Element\TableCaption $element): string {
        return "\\caption{{$this->renderElement($element)}}\n";
    }

    /**
     * @param \DocConverter\Document\Element\TableCell $element
     * @return string
     */
    public function renderTableCell(\DocConverter\Document\Element\TableCell $element): string {
        return $this->renderElement($element);
    }

    /**
     * @param \DocConverter\Document\Element\TableRow $element
     * @return string
     */
    public function renderTableRow(\DocConverter\Document\Element\TableRow $element): string {
        $tableCells = [];
        foreach ($element->getChildren() as $el) {
            $tableCells[] = $this->renderElement($el);
        }
        return implode(" & ", $tableCells) . " \\\\ \\hline\n";
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
                //property name for method call => latex command
                "Bold" => "\\textbf",
                "Italics" => "\\textit",
                "Underline" => "\\underline",
                "Subscript" => "\\textsubscript",
                "Superscript" => "\\textsuperscript",
                "SmallCaps" => "\\textsc"
            ];

            foreach ($stylesToApply as $styleName => $styleCommand) {
                if ($style->{"is" . $styleName}()) { //method call eg. "isBold()"
                    $text = "{$styleCommand}{{$text}}";
                }
            }
        }
        return $text;
    }

    /**
     * @param \DocConverter\Document\Element\UnorderedList $element
     * @return string
     */
    public function renderUnorderedList(\DocConverter\Document\Element\UnorderedList $element): string {
        return "\\begin{enumerate}\n{$this->renderElement($element)}\\end{enumerate}\n";
    }

}
