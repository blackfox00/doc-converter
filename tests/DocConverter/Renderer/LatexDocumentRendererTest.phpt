<?php

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Rendering elements into LaTeX command test
 * @testCase
 */
class LatexDocumentRendererTest extends \Tester\TestCase {

    /** @var \DocConverter\Renderer\LatexRenderer\DocumentRenderer */
    private $latexDocumentRenderer;

    public function __construct() {
        $this->latexDocumentRenderer = new \DocConverter\Renderer\LatexRenderer\DocumentRenderer();
    }

    public function testRenderCitation() {
        Assert::equal("", "");
    }

    public function testRenderFootnite() {
        Assert::equal("", "");
    }

    public function testRenderEndnote() {
        Assert::equal("", "");
    }

    public function testRenderLineBreak() {
        Assert::equal(" \\\\ ", $this->latexDocumentRenderer->renderLineBreak(new \DocConverter\Document\Element\LineBreak));
    }

    public function testRenderPageBreak() {
        Assert::equal("\n\\pagebreak\n\n", $this->latexDocumentRenderer->renderPageBreak(new \DocConverter\Document\Element\PageBreak));
    }

    public function testRenderText() {
        $text1 = new \DocConverter\Document\Element\Text("Text of element text");
        Assert::equal("Text of element text", $this->latexDocumentRenderer->renderText($text1));

        $text2 = new \DocConverter\Document\Element\Text("Text of element text");
        $text2->addStyle(new DocConverter\Document\Style\Style(['bold' => true]));
        Assert::equal("\\textbf{Text of element text}", $this->latexDocumentRenderer->renderText($text2));

        $text3 = new \DocConverter\Document\Element\Text("Text of element text");
        $text3->addStyle(new DocConverter\Document\Style\Style(['bold' => true, 'underline' => true]));
        Assert::equal("\\underline{\\textbf{Text of element text}}", $this->latexDocumentRenderer->renderText($text3));

        $text4 = new \DocConverter\Document\Element\Text("Text of element text");
        $text4->addStyle(new DocConverter\Document\Style\Style(['italics' => true]));
        Assert::equal("\\textit{Text of element text}", $this->latexDocumentRenderer->renderText($text4));
    }

    public function testRenderParagraph() {
        $paragraph = new DocConverter\Document\Element\Paragraph();
        $paragraph->addChild(new \DocConverter\Document\Element\Text("Text"));
        Assert::equal("Text\n", $this->latexDocumentRenderer->renderParagraph($paragraph));
    }

    public function testRenderHyperlink() {
        $hyperlink = new DocConverter\Document\Element\Hyperlink(2, "http://www.example.com");
        $hyperlink->addChild(new \DocConverter\Document\Element\Text("Text"));
        Assert::equal("\\href{http://www.example.com}{Text}", $this->latexDocumentRenderer->renderHyperlink($hyperlink));
    }

    public function testRenderMath() {
        $math1 = new DocConverter\Document\Element\Math("\\frac{a}{b}", DocConverter\Document\Element\Math::TYPE_INLINE);
        Assert::equal("\\begin{math}\\frac{a}{b}\\end{math}", $this->latexDocumentRenderer->renderMath($math1));
        $math2 = new DocConverter\Document\Element\Math("\\frac{a}{b}");
        Assert::equal("\\begin{equation}\n\\frac{a}{b}\n\\end{equation}\n", $this->latexDocumentRenderer->renderMath($math2));
    }

    public function testRenderFigure() {
        $figure = new \DocConverter\Document\Element\Figure();
        $image = new \DocConverter\Document\Element\Image(1, "image.png");
        $caption = new DocConverter\Document\Element\FigureCaption();
        $caption->addChild(new \DocConverter\Document\Element\Text("Figure caption"));

        $figure->addChild($image);
        $figure->addChild($caption);

        Assert::equal("\\begin{figure}[H]\n\includegraphics{image.png}\n\\caption{Figure caption}\n\\end{figure}\n", $this->latexDocumentRenderer->renderFigure($figure));
    }

    public function testRenderHeader() {
        $header1 = new \DocConverter\Document\Element\Header();
        $header1->addChild(new \DocConverter\Document\Element\Text("Header"));
        $style1 = new DocConverter\Document\Style\Style(['HeaderDepth' => 1]);
        $header1->addStyle($style1);
        Assert::equal("\n\\section{Header}\n", $this->latexDocumentRenderer->renderHeader($header1));

        $header2 = new \DocConverter\Document\Element\Header();
        $header2->addChild(new \DocConverter\Document\Element\Text("Header"));
        $style2 = new DocConverter\Document\Style\Style(['HeaderDepth' => 2]);
        $header2->addStyle($style2);
        Assert::equal("\n\\subsection{Header}\n", $this->latexDocumentRenderer->renderHeader($header2));

        $header3 = new \DocConverter\Document\Element\Header();
        $header3->addChild(new \DocConverter\Document\Element\Text("Header"));
        $style3 = new DocConverter\Document\Style\Style(['HeaderDepth' => 3]);
        $header3->addStyle($style3);
        Assert::equal("\n\\subsubsection{Header}\n", $this->latexDocumentRenderer->renderHeader($header3));

        $header4 = new \DocConverter\Document\Element\Header();
        $header4->addChild(new \DocConverter\Document\Element\Text("Header"));
        $style4 = new DocConverter\Document\Style\Style(['HeaderDepth' => 4]);
        $header4->addStyle($style4);
        Assert::equal("\n\\paragraph{Header}\n", $this->latexDocumentRenderer->renderHeader($header4));

        $header5 = new \DocConverter\Document\Element\Header();
        $header5->addChild(new \DocConverter\Document\Element\Text("Header"));
        $style5 = new DocConverter\Document\Style\Style(['HeaderDepth' => 5]);
        $header5->addStyle($style5);
        Assert::equal("\n\\subparagraph{Header}\n", $this->latexDocumentRenderer->renderHeader($header5));

        $header6 = new \DocConverter\Document\Element\Header();
        $header6->addChild(new \DocConverter\Document\Element\Text("Header"));
        $style6 = new DocConverter\Document\Style\Style(['HeaderDepth' => 6]);
        $header6->addStyle($style6);
        Assert::equal("\n\\subparagraph{Header}\n", $this->latexDocumentRenderer->renderHeader($header6));
    }

    public function testOrderedList() {
        $list = new \DocConverter\Document\Element\OrderedList(1, 1);
        $listItem1 = new \DocConverter\Document\Element\ListItem();
        $listItem1->addChild(new \DocConverter\Document\Element\Text("List item 1"));
        $listItem2 = new \DocConverter\Document\Element\ListItem();
        $listItem2->addChild(new \DocConverter\Document\Element\Text("List item 2"));
        $list->addChild($listItem1);
        $list->addChild($listItem2);
        Assert::equal("\begin{itemize}\n\\item List item 1\n\\item List item 2\n\\end{itemize}\n", $this->latexDocumentRenderer->renderOrderedList($list));
    }

    public function testUnorderedList() {
        $list = new \DocConverter\Document\Element\UnorderedList(1, 1);
        $listItem1 = new \DocConverter\Document\Element\ListItem();
        $listItem1->addChild(new \DocConverter\Document\Element\Text("List item 1"));
        $listItem2 = new \DocConverter\Document\Element\ListItem();
        $listItem2->addChild(new \DocConverter\Document\Element\Text("List item 2"));
        $list->addChild($listItem1);
        $list->addChild($listItem2);
        Assert::equal("\begin{enumerate}\n\\item List item 1\n\\item List item 2\n\\end{enumerate}\n", $this->latexDocumentRenderer->renderUnorderedList($list));
    }

    public function testRenderTable() {
        $table = new \DocConverter\Document\Element\Table();
        $table->setColumns(2);

        $tableRow1 = new \DocConverter\Document\Element\TableRow();
        $tableCell11 = new \DocConverter\Document\Element\TableCell();
        $tableCell11->addChild((new DocConverter\Document\Element\Paragraph())->addChild(new \DocConverter\Document\Element\Text("Cell 11")));
        $tableRow1->addChild($tableCell11);
        $tableCell12 = new \DocConverter\Document\Element\TableCell();
        $tableCell12->addChild((new DocConverter\Document\Element\Paragraph())->addChild(new \DocConverter\Document\Element\Text("Cell 12")));
        $tableRow1->addChild($tableCell12);

        $tableRow2 = new \DocConverter\Document\Element\TableRow();
        $tableCell21 = new \DocConverter\Document\Element\TableCell();
        $tableCell21->addChild((new DocConverter\Document\Element\Paragraph())->addChild(new \DocConverter\Document\Element\Text("Cell 21")));
        $tableRow2->addChild($tableCell21);
        $tableCell22 = new \DocConverter\Document\Element\TableCell();
        $tableCell22->addChild((new DocConverter\Document\Element\Paragraph())->addChild(new \DocConverter\Document\Element\Text("Cell 22")));
        $tableRow2->addChild($tableCell22);

        $caption = new \DocConverter\Document\Element\TableCaption();
        $caption->addChild(new \DocConverter\Document\Element\Text("Table caption"));

        $table->addChild($caption);
        $table->addChild($tableRow1);
        $table->addChild($tableRow2);
        Assert::equal("\\begin{tabular}{|c|c|}\n\\caption{Table caption}\nCell 11\n & Cell 12\n \\\\ \\hline\nCell 21\n & Cell 22\n \\\\ \\hline\n\\end{tabular}\n", $this->latexDocumentRenderer->renderTable($table));
    }

}

$testCase = new LatexDocumentRendererTest();
$testCase->run();
