<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriver;

class HolderDriver extends PartialDriver implements HolderDriverInterface
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            /**
             * @var string $content Contenu de remplacement.
             */
            'content'          => '',
            /**
             * @var int $width Rapport de largeur relatif à la hauteur.
             */
            'width'            => 100,
            /**
             * @var int $height Rapport de hauteur relatif à la largeur.
             */
            'height'           => 100,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/holder");
    }
}