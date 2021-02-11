<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\Drivers\Tab\TabCollectionInterface;
use Pollen\Partial\Drivers\Tab\TabFactoryInterface;
use Pollen\Partial\PartialDriverInterface;

interface TabDriverInterface extends PartialDriverInterface
{
    /**
     * Ajout d'un élément.
     *
     * @param TabFactoryInterface|array $def
     *
     * @return static
     */
    public function addItem($def): TabDriverInterface;

    /**
     * Récupération du gestionnaire des éléments déclarés.
     *
     * @return TabCollectionInterface
     */
    public function getTabCollection(): TabCollectionInterface;

    /**
     * Récupération du style de l'onglet.
     *
     * @param int $depth
     *
     * @return string
     */
    public function getTabStyle(int $depth = 0): string;

    /**
     * Définition du gestionnaire des éléments déclarés.
     *
     * @param TabCollectionInterface $tabCollection
     *
     * @return static
     */
    public function setTabCollection(TabCollectionInterface $tabCollection): TabDriverInterface;
}
