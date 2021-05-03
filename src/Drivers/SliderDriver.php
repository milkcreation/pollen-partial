<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriver;
use Pollen\Validation\Validator as v;

class SliderDriver extends PartialDriver implements SliderDriverInterface
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            /**
             * @var string[]|callable[] $items Liste des éléments. Liste de sources d'image|Liste de contenu HTML|Liste
             * de fonctions. défaut : @see https://picsum.photos/images
             */
            'items'   => [
                'https://picsum.photos/800/800/?image=768',
                'https://picsum.photos/800/800/?image=669',
                'https://picsum.photos/800/800/?image=646',
                'https://picsum.photos/800/800/?image=883',
            ],
            /**
             * @var array $options Liste des attributs de configuration du pilote d'affichage.
             * @see http://kenwheeler.github.io/slick/#settings
             */
            'options' => [],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        $items = $this->get('items', []);
        foreach ($items as &$item) {
            if (is_callable($item)) {
                $item = $item();
            } elseif (is_array($item)) {
                continue;
            } elseif (v::url()->validate($item)) {
                $item = "<img src=\"{$item}\" alt=\"\"/>";
            }
        }
        unset($item);

        $this->set([
            'items'              => $items,
            'attrs.data-control' => 'slider',
            'attrs.data-slick'   => htmlentities(json_encode($this->get('options', []))),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/slider");
    }
}