<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriver;

/**
 * @todo
 */
class MenuDriver extends PartialDriver implements MenuDriverInterface
{
    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/menu");
    }
}