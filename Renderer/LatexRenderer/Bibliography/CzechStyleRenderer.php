<?php

namespace DocConverter\Renderer\LatexRenderer\Bibliography;

/**
 * Bibliography renderer to follow the rules of ČSN ISO 690:2011 norm
 */
class CzechStyleRenderer implements IBibliographyRenderer {

    public function render(\DocConverter\Document\Bibliography\AbstractSource $source) {
        $r = "\\bibitem{" . $source->getTag() . "}\n";
        $method = "render" . \DocConverter\Utils::getPureClassName($source);
        $r .= $this->{$method}($source);
        return $r . "\n";
    }

    /**
     * @param \DocConverter\Document\Bibliography\JournalSource $source
     * @return string
     */
    private function renderBookSource(\DocConverter\Document\Bibliography\BookSource $source) {
        $rendered = "";
        return $rendered;
    }

    /**
     * @param \DocConverter\Document\Bibliography\JournalSource $source
     * @return string
     */
    private function renderInternetSource(\DocConverter\Document\Bibliography\InternetSource $source) {
        $rendered = "";
        return $rendered;
    }

    /**
     * @param \DocConverter\Document\Bibliography\JournalSource $source
     * @return string
     */
    private function renderJournalSource(\DocConverter\Document\Bibliography\JournalSource $source) {
        $rendered = "";
        return $rendered;
    }

    /**
     * @param \DocConverter\Document\Bibliography\BibliographyAuthor[] $authors
     */
    private function renderAuthors(array $authors) {
        $r = "";
        foreach ($authors as $author) {
            $r .= $author->getLastName();
        }
        return $r;
    }

}
