<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialDriverInterface;

/**
 * @todo
 */
class GridTableDriver extends PartialDriver implements TableDriverInterface
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            /**
             * @var bool $header Activation de l'entête de table.
             */
            'header'  => true,
            /**
             * @var bool $footer Activation du pied de table.
             */
            'footer'  => true,
            /**
             * @var string[] $columns Intitulé des colonnes.
             */
            'columns' => [
                'Lorem',
                'Ipsum',
            ],
            /**
             * @var array[] $datas Données de la table.
             */
            'datas'   => [
                [
                    'lorem dolor',
                    'ipsum dolor',
                ],
                [
                    'lorem amet',
                    'ipsum amet',
                ],
            ],
            /**
             * @var string $none Intitulé de la table lorsque la table ne contient aucune donnée.
             */
            'none'    => __('Aucun élément à afficher dans le tableau', 'tify'),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        $this->set('count', count($this->get('columns', [])));
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/table");
    }
}