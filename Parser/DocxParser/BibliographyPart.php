<?php

namespace DocConverter\Parser\DocxParser;

/**
 * Reads bibliography xml part
 */
class BibliographyPart extends CommonPart {

    /** @var string $xmlFile */
    protected $xmlFile = "customXml/item{%i}.xml"; //only a file mask - correct file is determined in the consturctor

    /** @var array $sourceClasses */
    private $sourceClasses = [
        "Book" => "BookSource",
        "InternetSite" => "InternetSource"
    ];

    /**
     * Custom constructor - we first need to find in which file are the bibliography sources located
     * @param \DocConverter\Parser\DocxParser $parser
     * @throws \Exception
     */
    public function __construct(\DocConverter\Parser\DocxParser $parser) {
        if (file_exists($parser->getFile()) === false) {
            throw new \Exception('Cannot find archive file.');
        }

        $zip = new \ZipArchive();
        $zip->open($parser->getFile());
        $i = 1;
        while ($i < 7) {
            $xmlFile = str_replace("{%i}", $i, $this->xmlFile);
            $content = $zip->getFromName($xmlFile);
            if (\Nette\Utils\Strings::contains($content, "<b:Sources")) {
                break;
            }
            $i++;
        }
        $zip->close();
        $this->xmlFile = $xmlFile;
        parent::__construct($parser);
    }

    /**
     * @return \DocConverter\Document\Bibliography\AbstractSource[] 
     */
    public function parse(): array {
        $sources = [];
        $nodes = $this->xmlReader->getElements("*");
        foreach ($nodes as $node) {
            $sourceType = $this->sourceClasses[$this->xmlReader->getElement("b:SourceType", $node)->nodeValue] ?? "Book";
            $sourceClass = "\\DocConverter\\Document\\Bibliography\\" . $sourceType;
            if (!class_exists($sourceClass)) {
                throw new \DocConverter\Exception\InvalidStateException("Bibliography source class {$sourceClass} doesn't exist");
            }

            $source = new $sourceClass;
            foreach ($this->xmlReader->getElements("b:Author/b:Author", $node) as $authorNode) {
                $author = new \DocConverter\Document\Bibliography\BibliographyAuthor($this->xmlReader->getElement("b:NameList/b:Person/b:First", $authorNode)->nodeValue, $this->xmlReader->getElement("b:NameList/b:Person/b:Last", $authorNode)->nodeValue, true);
                $source->addAuthor($author);
            }

            //set mandatory properties for all types
            $source->setTag($this->xmlReader->getElement("b:Tag", $node)->nodeValue)
                    ->setTitle($this->xmlReader->getElement("b:Title", $node)->nodeValue)
                    ->setYear($this->xmlReader->getElement("b:Year", $node)->nodeValue);

            //set other properties depending on source type
            switch ($sourceType) {
                case "Book":

                    break;
                case "InternetSite":
                    break;

                case "Journal":

                    break;
            }

            $sources[] = $source;
        }

        return $sources;
    }

}
