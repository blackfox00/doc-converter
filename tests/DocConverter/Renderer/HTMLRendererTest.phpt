<?php

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Rendering elements to HTML test
 * @testCase
 */
class HTMLRendererTest extends \Tester\TestCase {

    /** @var \DocConverter\Renderer\HtmlRenderer */
    private $htmlRenderer;

    public function __construct() {
        $this->htmlRenderer = new \DocConverter\Renderer\HtmlRenderer();
    }

    public function testRenderBody() {
        $body = new DocConverter\Document\Element\Body();
        $body->addChild(new \DocConverter\Document\Element\Text("Text"));
        Assert::equal("<div>Text</div>", $this->htmlRenderer->renderBody($body));
    }

    public function testRenderCitation() { //TODO
        Assert::equal("", "");
    }

    public function testRenderFootnite() { //TODO
        Assert::equal("", "");
    }

    public function testRenderEndnote() { //TODO
        Assert::equal("", "");
    }

    public function testRenderLineBreak() {
        Assert::equal("<br>", $this->htmlRenderer->renderLineBreak(new \DocConverter\Document\Element\LineBreak));
    }

    public function testRenderPageBreak() {
        Assert::equal(\DocConverter\Renderer\HtmlRenderer::PAGE_BREAK, $this->htmlRenderer->renderPageBreak(new \DocConverter\Document\Element\PageBreak));
    }

    public function testRenderText() {
        $text = new \DocConverter\Document\Element\Text("Text of element text");
        Assert::equal("Text of element text", $this->htmlRenderer->renderText($text));

        $text = new \DocConverter\Document\Element\Text("Text of element text");
        $text->addStyle(new DocConverter\Document\Style\Style(['bold' => true]));
        Assert::equal("<span style=\"font-weight: bold\">Text of element text</span>", $this->htmlRenderer->renderText($text));

        $text = new \DocConverter\Document\Element\Text("Text of element text");
        $text->addStyle(new DocConverter\Document\Style\Style(['bold' => true, 'underline' => true]));
        Assert::equal("<span style=\"font-weight: bold; text-decoration: underline\">Text of element text</span>", $this->htmlRenderer->renderText($text));

        $text = new \DocConverter\Document\Element\Text("Text of element text");
        $text->addStyle(new DocConverter\Document\Style\Style(['italics' => true]));
        Assert::equal("<span style=\"font-style: italic\">Text of element text</span>", $this->htmlRenderer->renderText($text));
    }

    public function testRenderParagraph() {
        $paragraph = new DocConverter\Document\Element\Paragraph();
        $paragraph->addChild(new \DocConverter\Document\Element\Text("Text"));
        Assert::equal("<p>Text</p>", $this->htmlRenderer->renderParagraph($paragraph));
    }

    public function testRenderHyperlink() {
        $hyperlink = new DocConverter\Document\Element\Hyperlink(2, "http://www.example.com");
        $hyperlink->addChild(new \DocConverter\Document\Element\Text("Text"));
        Assert::equal("<a href=\"http://www.example.com\">Text</a>", $this->htmlRenderer->renderHyperlink($hyperlink));
    }

    public function testRenderMath() {
        $math = new DocConverter\Document\Element\Math("\\frac{a}{b}", DocConverter\Document\Element\Math::TYPE_INLINE);
        Assert::equal("\\(\\frac{a}{b}\\)", $this->htmlRenderer->renderMath($math));
        $math = new DocConverter\Document\Element\Math("\\frac{a}{b}");
        Assert::equal("\$\$\\frac{a}{b}\$\$", $this->htmlRenderer->renderMath($math));
    }

    public function testRenderFigure() {
        $figure = new \DocConverter\Document\Element\Figure();
        $image = new \DocConverter\Document\Element\Image(1, "", base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII="));
        $caption = new DocConverter\Document\Element\FigureCaption();
        $caption->addChild(new \DocConverter\Document\Element\Text("Figure caption"));

        $figure->addChild($image);
        $figure->addChild($caption);

        Assert::equal("<figure><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=\"><figcaption>Figure caption</figcaption></figure>", $this->htmlRenderer->renderFigure($figure));
    }

    public function testRenderHeader() {
        $header = new \DocConverter\Document\Element\Header();
        $header->addChild(new \DocConverter\Document\Element\Text("Header"));
        Assert::equal("<h1>Header</h1>", $this->htmlRenderer->renderHeader($header));
    }

    public function testOrderedList() {
        $list = new \DocConverter\Document\Element\OrderedList(1, 1);
        $listItem1 = new \DocConverter\Document\Element\ListItem();
        $listItem1->addChild(new \DocConverter\Document\Element\Text("List item 1"));
        $listItem2 = new \DocConverter\Document\Element\ListItem();
        $listItem2->addChild(new \DocConverter\Document\Element\Text("List item 2"));
        $list->addChild($listItem1);
        $list->addChild($listItem2);
        Assert::equal("<ol><li>List item 1</li><li>List item 2</li></ol>", $this->htmlRenderer->renderOrderedList($list));
    }

    public function testUnorderedList() {
        $list = new \DocConverter\Document\Element\UnorderedList(1, 1);
        $listItem1 = new \DocConverter\Document\Element\ListItem();
        $listItem1->addChild(new \DocConverter\Document\Element\Text("List item 1"));
        $listItem2 = new \DocConverter\Document\Element\ListItem();
        $listItem2->addChild(new \DocConverter\Document\Element\Text("List item 2"));
        $list->addChild($listItem1);
        $list->addChild($listItem2);
        Assert::equal("<ul><li>List item 1</li><li>List item 2</li></ul>", $this->htmlRenderer->renderUnorderedList($list));
    }

    public function testRenderTable() {
        $table = new \DocConverter\Document\Element\Table();

        $tableRow1 = new \DocConverter\Document\Element\TableRow();
        $tableCell11 = new \DocConverter\Document\Element\TableCell();
        $tableCell11->addChild(new \DocConverter\Document\Element\Text("Cell 11"));
        $tableRow1->addChild($tableCell11);
        $tableCell12 = new \DocConverter\Document\Element\TableCell();
        $tableCell12->addChild(new \DocConverter\Document\Element\Text("Cell 12"));
        $tableRow1->addChild($tableCell12);

        $tableRow2 = new \DocConverter\Document\Element\TableRow();
        $tableCell21 = new \DocConverter\Document\Element\TableCell();
        $tableCell21->addChild(new \DocConverter\Document\Element\Text("Cell 21"));
        $tableRow2->addChild($tableCell21);
        $tableCell22 = new \DocConverter\Document\Element\TableCell();
        $tableCell22->addChild(new \DocConverter\Document\Element\Text("Cell 22"));
        $tableRow2->addChild($tableCell22);

        $caption = new \DocConverter\Document\Element\TableCaption();
        $caption->addChild(new \DocConverter\Document\Element\Text("Table caption"));

        $table->addChild($caption);
        $table->addChild($tableRow1);
        $table->addChild($tableRow2);
        Assert::equal("<table><caption>Table caption</caption><tr><td>Cell 11</td><td>Cell 12</td></tr><tr><td>Cell 21</td><td>Cell 22</td></tr></table>", $this->htmlRenderer->renderTable($table));
    }

}

$testCase = new HTMLRendererTest;
$testCase->run();
