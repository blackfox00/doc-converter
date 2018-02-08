<?php

namespace DocConverter\Parser\DocxParser\Math;

/**
 * Converts Office MathML to Latex math commands
 */
class OOMLToLatex {

    //method call table
    private $mainMethods = [
        "m:acc" => "readAccent",
        "m:bar" => "readBar",
        "m:box" => "readBox",
        "m:borderBox" => "readBorderBox",
        "m:d" => "readDelimeter",
        "m:eqArr" => "readEqArr",
        "m:f" => "readFraction",
        "m:func" => "readFunction",
        "m:groupChr" => "readGroupChr",
        "m:limLow" => "readLimLow",
        "m:limUpp" => "readLimUpp",
        "m:m" => "readMatrix",
        "m:nary" => "readNary",
        "m:phant" => "readPhantom",
        "m:r" => "readRun",
        "m:rad" => "readRad",
        "m:sPre" => "readSPre",
        "m:sSub" => "readSSub",
        "m:sSubSup" => "readSSubSup",
        "m:sSup" => "readSSup"
    ];

    /** @var \PhpOffice\Common\XmlReader */
    protected $xmlReader;

    /** @var \DOMNode $contextNode m:oMath node */
    protected $contextNode;

    /**
     * @param \PhpOffice\Common\XmlReader $xmlReader
     * @param \DOMNode $contextNode
     */
    public function __construct(\PhpOffice\Common\XmlReader $xmlReader, \DOMNode $contextNode) {
        $this->xmlReader = $xmlReader;
        $this->contextNode = $contextNode;
    }

    /**
     * @return string
     */
    public function convert(): string {
        $result = "";
        if ($this->contextNode->nodeName === "m:oMathPara") {
            $oMathNodes = $this->xmlReader->getElements("m:oMath", $this->contextNode);
            $resultArray = [];
            foreach ($oMathNodes as $oMathNode) {
                $equation = $this->readNodes("*", $oMathNode);
                //TODO align
                $resultArray[] = $equation;
            }
            if (count($resultArray) > 1) {
                $result = $this->command(MathSymbols::$commands["array"], implode(" \\\\ ", $resultArray));
            } else {
                $result = implode(" \\\\ ", $resultArray);
            }
        } elseif ($this->contextNode->nodeName === "m:oMath") {
            $result = $this->readNodes("*", $this->contextNode);
        }

        return $result;
    }

    /**
     * ============================ READING METHODS ============================
     */

    /**
     * @param string $path
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readNodes(string $path, \DOMElement $contextNode): string {
        $result = "";
        $nodes = $this->xmlReader->getElements($path, $contextNode);
        foreach ($nodes as $node) {
            $result .= $this->readNode($node);
        }
        return $result;
    }

    /**
     * Decides what node reading function to call based on node name
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readNode(\DOMElement $contextNode): string {
        $nodeName = $contextNode->nodeName;
        $result = "";
        if (isset($this->mainMethods[$nodeName])) {
            $method = $this->mainMethods[$nodeName];
            $result = $this->{$method}($contextNode);
        }
        return $result;
    }

    /**
     * Read m:acc element
     * @param \DOMElement $contextNode
     * @return string

      protected function readAccent(\DOMElement $contextNode): string {
      $char = $this->xmlReader->getAttribute("m:val", $contextNode, "m:accPr/m:chr");
      return $this->command(MathSymbols::$commands["accent"], $char, $this->readBase($contextNode));
      }
     * */

    /**
     * Read m:acc element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readAccent(\DOMElement $contextNode): string {
        $char = $this->xmlReader->getAttribute("m:val", $contextNode, "m:accPr/m:chr");
        if (isset(MathSymbols::$symbols[$char])) {
            $char = MathSymbols::$symbols[$char];
            return $char . "{" . $this->readBase($contextNode) . "}";
        } else {
            return $char . $this->readBase($contextNode);
        }
    }

    /**
     * Read m:e element into array and not final strings (used in m:m functions 
     * and m:d functions so we can use implode function and properly separate 
     * their child m:e base elements)
     * @param \DOMElement $contextNode
     * @return array
     */
    protected function readBaseToArray(\DOMElement $contextNode): array {
        $base = [];
        $nodes = $this->xmlReader->getElements("m:e/*", $contextNode);
        foreach ($nodes as $node) {
            $base[] = $this->readNode($node);
        }
        return $base;
    }

    /**
     * Read m:e elements
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readBase(\DOMElement $contextNode): string {
        return $this->readNodes("m:e/*", $contextNode);
    }

    /**
     * Read m:bar element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readBar(\DOMElement $contextNode): string {
        $pos = $this->xmlReader->getAttribute("m:val", $contextNode, "m:barPr/m:pos"); //bot or top
        if (!$pos) {
            $pos = "top"; //default
        }
        return MathSymbols::$symbols[$pos == "bot" ? "\u{00331}" : "\u{00304}"] . "{" . $this->readBase($contextNode) . "}";
    }

    /**
     * Read m:borderBox element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readBorderBox(\DOMElement $contextNode): string {
        return $this->command(MathSymbols::$commands["box"], $this->readBase($contextNode));
    }

    /**
     * Read m:box element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readBox(\DOMElement $contextNode): string {
        if ($this->xmlReader->elementExists("m:boxPr", $contextNode)) {
            $boxPr = $this->xmlReader->getElement("m:boxPr", $contextNode);
            if($boxPr) {
                //TODO handle alingment 
            }
        }
        return $this->readBase($contextNode);
    }

    /**
     * Read m:eqArr element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readEqArr(\DOMElement $contextNode): string {
        $rows = implode(" \\\\ ", $this->readBaseToArray($contextNode));
        return $this->command(MathSymbols::$commands["array"], $rows);
    }

    //    protected function readEqArr(\DOMElement $contextNode): string {
//        $checkParentNode = $contextNode;
//        $isLim = false;
//        while ($checkParentNode->parentNode) {
//            if ($checkParentNode->parentNode->nodeName == "m:lim") {
//                $isLim = true;
//            }
//            $checkParentNode = $checkParentNode->parentNode;
//        }
//        $bases = $this->readBaseArray($contextNode);
//        return $isLim ? $this->command(MathSymbols::$commands["substack"], implode(" \\\\\\ ", $bases)) : implode(" \\\\\\ ", $bases);
//    }

    /**
     * Read m:d element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readDelimeter(\DOMElement $contextNode): string {
        //defaults
        $leftDelimeter = "\\left(";
        $rightDelimeter = "\\right)";
        $separator = "|";

        if ($this->xmlReader->elementExists("m:dPr/m:begChr", $contextNode)) {
            $leftDelimeter = "\\left" . $this->format($this->xmlReader->getAttribute("m:val", $contextNode, "m:dPr/m:begChr"));
        }

        if ($this->xmlReader->elementExists("m:dPr/m:endChr", $contextNode)) {
            $rightDelimeter = "\\right" . $this->format($this->xmlReader->getAttribute("m:val", $contextNode, "m:dPr/m:endChr"));
        }

        if ($this->xmlReader->elementExists("m:dPr/m:sepChr", $contextNode)) {
            $separator = $this->escapeCharacters($this->xmlReader->getAttribute("m:val", $contextNode, "m:dPr/m:sepChr"));
        }

        $base = [];
        $baseNodes = $this->xmlReader->getElements("m:e", $contextNode);
        foreach ($baseNodes as $baseNode) {
            $base[] = $this->readNodes("*", $baseNode);
        }
        return $leftDelimeter . implode($separator, $base) . $rightDelimeter;
    }

    /**
     * Read m:frac element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readFraction(\DOMElement $contextNode): string {
        return $this->command(MathSymbols::$commands["fraction"], $this->readNodes("m:num/*", $contextNode), $this->readNodes("m:den/*", $contextNode));
    }

    /**
     * Read m:func element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readFunction(\DOMElement $contextNode): string {
        $function = $this->readNodes("m:fName/*", $contextNode);
        $base = $this->readNodes("m:e/*", $contextNode);
        return $function . $base;
    }

    /**
     * Read m:groupChr element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readGroupChr(\DOMElement $contextNode): string {
        $symbol = ""; //default symbol
        if ($this->xmlReader->elementExists("m:groupChrPr/m:chr", $contextNode)) {
            $symbol = $this->xmlReader->getAttribute("m:val", $contextNode, "m:groupChrPr/m:chr");
        }
        $symbol = MathSymbols::$symbols[$symbol] ?? $symbol;
        $text = $this->readBase($contextNode);
        return $this->command(MathSymbols::$commands["overtext"], $this->command(MathSymbols::$commands["text"], $text), $symbol);
    }

    /**
     * Read m:limUpp element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readLimUpp(\DOMElement $contextNode): string {
        $base = $this->readNodes("m:e/*", $contextNode);
        $lim = $this->readNodes("m:lim/*", $contextNode);
        return $base . $this->command(MathSymbols::$commands["superscript"], $lim);
    }

    /**
     * Read m:limLow element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readLimLow(\DOMElement $contextNode): string {
        $base = $this->readNodes("m:e/*", $contextNode);
        $lim = $this->readNodes("m:lim/*", $contextNode);
        return $base . $this->command(MathSymbols::$commands["subscript"], $lim);
    }

    /**
     * Read m:m element
     * @param \DOMEntity $contextNode
     * @return string
     */
    protected function readMatrix(\DOMElement $contextNode): string {
        $rowNodes = $this->xmlReader->getElements("m:mr", $contextNode);
        $rows = [];
        $matrix = "";
        foreach ($rowNodes as $rowNode) {
            $base = $this->readBaseToArray($rowNode);
            $rows[] = implode(" && ", $base);
        }
        $matrix .= implode(" \\\\ ", $rows);
        return $this->command(MathSymbols::$commands["matrix"], $matrix);
    }

    /**
     * Read m:nary element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readNary(\DOMElement $contextNode): string {
        $symbol = "\\int "; //default
        if ($this->xmlReader->elementExists("m:naryPr/m:chr", $contextNode)) {
            $symbol = $this->xmlReader->getAttribute("m:val", $contextNode, "m:naryPr/m:chr");
        }
        $symbol = MathSymbols::$symbols[$symbol] ?? $symbol;

        if ($this->xmlReader->elementExists("m:sub", $contextNode)) {
            $symbol .= $this->command(MathSymbols::$commands["subscript"], $this->readNodes("m:sub/*", $contextNode));
        }
        if ($this->xmlReader->elementExists("m:sup", $contextNode)) {
            $symbol .= $this->command(MathSymbols::$commands["superscript"], $this->readNodes("m:sup/*", $contextNode));
        }

        return $symbol . $this->readBase($contextNode);
    }

    /**
     * Read m:rad element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readRad(\DOMElement $contextNode): string {
        $deg = $this->xmlReader->elementExists("m:deg/m:r", $contextNode);
        if ($deg) {
            return $this->command(MathSymbols::$functions["sqrt_deg"], $this->readNodes("m:deg/*", $contextNode), $this->readNodes("m:e/*", $contextNode));
        } else {
            return $this->command(MathSymbols::$functions["sqrt"], $this->readNodes("m:e/*", $contextNode));
        }
    }

    /**
     * Read m:r element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readRun(\DOMElement $contextNode): string {
        $text = "";
        $nodes = $this->xmlReader->getElements("m:t", $contextNode);
        $isNormalText = $this->xmlReader->elementExists("m:rPr/m:nor", $contextNode);

        foreach ($nodes as $node) {
            if ($isNormalText) {
                $text = $this->command(MathSymbols::$commands["text"], $text);
            } else if (isset(MathSymbols::$functions[$node->nodeValue])) { //add check if there is a parent m:fName in parent node tree?
                $text = MathSymbols::$functions[$node->nodeValue];
            } else {
                $tmp = $this->stringToArray($node->nodeValue);
                $replaced = [];
                foreach ($tmp as $char) {
                    $replaced[] = $this->format($char);
                }
                $replacedString = join("", $replaced);
                $text .= $replacedString;
            }
        }
        return $text;
    }

    /**
     * Read m:sSub element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readSSub(\DOMElement $contextNode): string {
        return $this->getFunctionName($this->readBase($contextNode)) . $this->command(MathSymbols::$commands["subscript"], $this->readNodes("m:sub/*", $contextNode));
    }

    /**
     * Read m:sSup element
     * @param \DOMElement $contextNode
     * @return string
     */
    protected function readSSup(\DOMElement $contextNode): string {
        return $this->getFunctionName($this->readBase($contextNode)) . $this->command(MathSymbols::$commands["superscript"], $this->readNodes("m:sup/*", $contextNode));
    }

    /**
     * =============================== HELPERS =================================
     */

    /**
     * Properly separate unicode string into single character array
     * @param string $string
     * @return array
     */
    private function stringToArray(string $string): array {
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, "UTF-8");
            $string = mb_substr($string, 1, $strlen, "UTF-8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }

    /**
     * @param string $fName
     * @return string
     */
    private function getFunctionName($fName): string {
        return MathSymbols::$functions[$fName] ?? $fName;
    }

    /**
     * Escapes special characters used in LaTeX
     * @param string $content
     * @return string

      protected function escapeCharacters($content): string {
      $map = array(
      "#" => "\\#",
      "$" => "\\$",
      "%" => "\\%",
      "&" => "\\&",
      "~" => "\\~{}",
      "_" => "\\_",
      "^" => "\\^{}",
      "\\" => "\\textbackslash{}",
      "{" => "\\{",
      "}" => "\\}",
      );
      return preg_replace_callback("/([\^\%~\\\\#\$%&_\{\}])/", function($matches) use ($map) {
      return $map[$matches[0]];
      }, $content);
      }
     */

    /**
     * Replace special characters (greek letters, arrows, accents etc) with proper LaTeX commands
     * @param string $char
     * @return string
     */
    private function format(string $char): string {
        return MathSymbols::$symbols[$char] ?? $char;
    }

    /**
     * Places arguments into latex command
     * @param string latex command
     * @param string arguments to place in latex command
     * @return string
     */
    private function command(): string {
        $args = func_get_args();
        $command = array_shift($args);
        foreach ($args as $arg) {
            $pos = strpos($command, MathSymbols::ARGUMENT_PLACEHOLDER);
            if ($pos !== false) {
                $command = substr_replace($command, $arg, $pos, strlen(MathSymbols::ARGUMENT_PLACEHOLDER));
            }
        }
        return $command;
    }

}
