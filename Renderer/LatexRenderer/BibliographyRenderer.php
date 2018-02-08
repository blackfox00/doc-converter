<?php

namespace DocConverter\Renderer\LatexRenderer;

/**
 * 
 */
class BibliographyRenderer {

    private $bibliographyRenderer = 'CzechStyleRenderer';

    public function renderBibliography(\DocConverter\Document\Document $document) {
        $sources = $document->getBibliographySources();
        $bibliographyRendererClass = "\\DocConverter\\Renderer\\LatexRenderer\\Bibliography\\{$this->bibliographyRenderer}";
        if (!class_exists($bibliographyRendererClass)) {
            throw new \DocConverter\Exception\InvalidStateException("Bibliography renderer class {$bibliographyRendererClass} doesn't exist");
        }
        $biblographyRenderer = new $bibliographyRendererClass;

        $rendered = "\\begin{thebibliography}{99}\n";
        foreach ($sources as $source) {
            $rendered .= $biblographyRenderer->render($source);
        }
        
        $rendered .= "\\end{thebibliography}\n";
        return $rendered;
    }

}
