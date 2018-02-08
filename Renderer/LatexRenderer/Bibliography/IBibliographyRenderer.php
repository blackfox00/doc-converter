<?php

namespace DocConverter\Renderer\LatexRenderer\Bibliography;

/**
 *
 * @author JH
 */
interface IBibliographyRenderer {

    public function render(\DocConverter\Document\Bibliography\AbstractSource $source);
}
