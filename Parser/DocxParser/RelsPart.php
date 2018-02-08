<?php

namespace DocConverter\Parser\DocxParser;

/**
 * Reads document rels part
 */
class RelsPart extends CommonPart {

    /** @var string $xmlFile */
    protected $xmlFile = "word/_rels/document.xml.rels";

    /**
     * @return array
     */
    public function parse(): array {
        $metaPrefix = 'http://schemas.openxmlformats.org/package/2006/relationships/metadata/';
        $officePrefix = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/';
        $targetPrefix = 'word/';

        $rels = [];

        $nodes = $this->xmlReader->getElements('*');
        foreach ($nodes as $node) {
            $rId = $this->xmlReader->getAttribute('Id', $node);
            $type = $this->xmlReader->getAttribute('Type', $node);
            $target = $this->xmlReader->getAttribute('Target', $node);

            // Remove URL prefixes from $type to make it easier to read
            $type = str_replace($metaPrefix, '', $type);
            $type = str_replace($officePrefix, '', $type);
            $docPart = str_replace('.xml', '', $target);

            // Do not add prefix to link source
            if (!in_array($type, ['hyperlink'])) {
                $target = $targetPrefix . $target;
            }

            // Push to return array
            $rels[$rId] = ['type' => $type, 'target' => $target, 'docPart' => $docPart];
        }
        ksort($rels);

        return $rels;
    }

}
