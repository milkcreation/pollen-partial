<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriverInterface;

interface PdfViewerDriverInterface extends PartialDriverInterface
{
    /**
     * Récupération de l'instance de la modale associée au PDF.
     *
     * @param array $args Attributs de configuration de la modale.
     *
     * @return ModalDriverInterface
     */
    public function modal(array $args = []): ModalDriverInterface;
}
