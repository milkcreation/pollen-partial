<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialDriverInterface;

class SpinnerDriver extends PartialDriver implements SpinnerDriverInterface
{
    /**
     * Liste des indicateurs de pré-chargement disponibles
     * @var array
     */
    protected $spinners = [
        'rotating-plane',
        'fading-circle',
        'folding-cube',
        'double-bounce',
        'wave',
        'wandering-cubes',
        'spinner-pulse',
        'chasing-dots',
        'three-bounce',
        'circle',
        'cube-grid',
    ];

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(
            parent::defaultParams(),
            [
                /**
                 * @var string $spinner Choix de l'indicateur de préchargement. 'rotating-plane|fading-circle|folding-cube|
                 * double-bounce|wave|wandering-cubes|spinner-pulse|chasing-dots|three-bounce|circle|cube-grid.
                 * @see http://tobiasahlin.com/spinkit/
                 */
                'spinner' => 'spinner-pulse',
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): PartialDriverInterface
    {
        parent::parseParams();

        if ($spinner = $this->get('spinner')) {
            $spinner_class = "sk-{$spinner}";
        } else {
            $spinner_class = "sk-spinner sk-{$spinner}";
        }

        $this->set(
            'attrs.class',
            ($exists = $this->get('attrs.class'))
                ? "{$exists} {$spinner_class}" : $spinner_class
        );
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partialManager()->resources("/views/spinner");
    }
}