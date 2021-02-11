<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriverInterface;

interface CurtainMenuDriverInterface extends PartialDriverInterface
{
    /**
     * Traitement de la liste des éléments.
     *
     * @return static
     */
    public function parseItems(): CurtainMenuDriverInterface;
}