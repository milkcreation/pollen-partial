<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\ImageLightbox;

use Pollen\Support\ParamsBagInterface;

interface ImageLightboxItemInterface extends ParamsBagInterface
{
    /**
     * Récupération des attributs HTML du lien.
     *
     * @param bool $linearize
     *
     * @return array|string
     */
    public function getAttrs(bool $linearize = true);

    /**
     * Récupération du groupe associé.
     *
     * @return string|null
     */
    public function getGroup(): ?string;

    /**
     * Affichage de la miniature.
     *
     * @return string
     */
    public function getContent(): string;
}