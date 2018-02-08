<?php

namespace DocConverter\Renderer;

/**
 * Renders Document into LaTeX code
 */
class LatexRenderer implements IRenderer {

    public function __construct() {
        
    }

    public function render(\DocConverter\Document\Document $document) {
        if (!$document) {
            throw new \DocConverter\Exception\InvalidStateException("Document to render must be set");
        }

        $documentRenderer = new LatexRenderer\DocumentRenderer();
        $rendered = $documentRenderer->renderDocument($document);

        $bibliographyRenderer = new LatexRenderer\BibliographyRenderer();
        $rendered .= $bibliographyRenderer->renderBibliography($document);

        return $rendered;
    }

}
