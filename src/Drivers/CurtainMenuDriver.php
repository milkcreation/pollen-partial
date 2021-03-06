<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\Drivers\CurtainMenu\CurtainMenuCollection;
use Pollen\Partial\Drivers\CurtainMenu\CurtainMenuCollectionInterface;
use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialDriverInterface;

class CurtainMenuDriver extends PartialDriver implements CurtainMenuDriverInterface
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            /**
             * @var array $items Liste des éléments.
             */
            'items'     => [],
            /**
             * @var mixed $selected
             */
            'selected'  => null,
            /**
             * @var string $theme Theme d'affichage. light|dark.
             */
            'theme'     => 'light'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        if ($theme = $this->get('theme')) {
            $this->set('attrs.class', trim($this->get('attrs.class') . " CurtainMenu--{$theme}"));
        }

        $this->set('attrs.data-control', 'curtain-menu');

        $this->set('attrs.data-id', $this->getId());

        $this->set('attrs.data-options', []);

        $this->parseItems();
    }

    /**
     * @inheritDoc
     */
    public function parseItems(): CurtainMenuDriverInterface
    {
        $items = $this->get('items', []);
        if (!$items instanceof CurtainMenuCollectionInterface) {
            $items = new CurtainMenuCollection($items, $this->get('selected'));
        }
        $this->set('items', $items->prepare($this));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/curtain-menu");
    }
}