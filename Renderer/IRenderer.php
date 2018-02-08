<?php

namespace DocConverter\Renderer;

/**
 * Document renderer interface
 */
interface IRenderer {

    public function render(\DocConverter\Document\Document $document);
}
