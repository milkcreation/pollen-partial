<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Dropdown;

use tiFy\Contracts\Support\Collection;
use Pollen\Partial\Drivers\DropdownDriverInterface;

interface DropdownCollectionInterface extends Collection
{
    /**
     * Définition du controleur de controleur d'affichage associé.
     *
     * @param DropdownDriverInterface $partial Controleur d'affichage associé.
     *
     * @return static
     */
    public function setPartial(DropdownDriverInterface $partial): DropdownCollectionInterface;
}
