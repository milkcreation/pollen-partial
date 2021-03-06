<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\Drivers\Dropdown\DropdownCollection;
use Pollen\Partial\Drivers\Dropdown\DropdownCollectionInterface;
use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialDriverInterface;

class DropdownDriver extends PartialDriver implements DropdownDriverInterface
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            'button'    => '',
            'items'     => [],
            'open'      => false,
            'trigger'   => false
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        $this->set('attrs.data-control', 'dropdown');
        $this->set('attrs.data-id', $this->getId());

        $classes = [
            'button'    => 'Dropdown-button',
            'listItems' => 'Dropdown-items',
            'item'      => 'Dropdown-item'
        ];
        foreach($classes as $key => &$class) {
            $class = sprintf($this->get("classes.{$key}", '%s'), $class);
        }
        $this->set('classes', $classes);

        $items = $this->get('items', []);

        if (!$items instanceof DropdownCollectionInterface) {
            $items = new DropdownCollection($items);
        }
        $this->set('items', $items->setPartial($this));

        $this->set('attrs.data-options', [
            'classes' => $this->get('classes', []),
            'open'    => $this->get('open'),
            'trigger' => $this->get('trigger'),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/dropdown");
    }
}