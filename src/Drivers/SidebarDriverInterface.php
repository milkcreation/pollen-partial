<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriverInterface;

interface SidebarDriverInterface extends PartialDriverInterface
{
    /**
     * Lien de bascule d'affichage de la sidebar.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return string
     */
    public function toggle(array $attrs = []): string;
}
