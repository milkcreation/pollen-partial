<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Dropdown;

use Pollen\Support\ParamsBagInterface;

interface DropdownItemInterface extends ParamsBagInterface
{
    /**
     * Résolution de sortie du controleur sous la forme d'une chaîne de caractères.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération du contenu d'affichage de l'élément
     *
     * @return string
     */
    public function getContent(): string;
}
