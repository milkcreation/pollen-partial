<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialDriverInterface;

class ProgressDriver extends PartialDriver implements ProgressDriverInterface
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            // @todo 'infos'       => true,
            'label'       => '',
            'max'         => 100,
            'meter-bar'   => true,
            'meter-label' => true,
            'value'       => 0,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        $meterBar = $this->get('meter-bar');
        if ($meterBar !== false) {
            if (!is_array($meterBar)) {
                $this->set('meter-bar', []);
            }
            $this->set([
                'meter-bar.attrs.data-control' => 'progress.meter.bar',
            ]);
        }

        $meterLabel = $this->get('meter-label');
        if ($meterLabel !== false) {
            $defaults = [
                'content' => $this->get('label'),
                'tag'     => 'span',
            ];

            $this->set('meter-label', is_array($meterLabel) ? array_merge($defaults, $meterLabel) : $defaults);

            $this->set([
                'meter-label.attrs.data-control' => 'progress.meter.label',
            ]);
        }

        $this->set('meter', !!($meterBar || $meterLabel));

        /* @todo
        $infos = $this->get('infos');
        if ($infos !== false) {
            if (!is_array($infos)) {
                $this->set('infos', []);
            }
            $this->set([
                'infos.attrs.data-control' => 'progress.infos',
            ]);
        }*/

        $this->set([
            'attrs.data-control' => $this->get('attrs.data-control', 'progress'),
            'attrs.data-options' => [
                'label' => (bool)$this->get('label') ? 'fixed' : 'auto',
                'max'   => $this->get('max', 100),
                'value' => $this->get('value', 0),
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/progress");
    }
}