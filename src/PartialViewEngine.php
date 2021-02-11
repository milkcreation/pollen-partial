<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\View\ViewEngine;
use Pollen\View\ViewTemplateInterface;

class PartialViewEngine extends ViewEngine implements PartialViewEngineInterface
{
    /**
     * Liste des méthodes de délégations permises.
     * @var array
     */
    protected $delegatedMixins = [
        'after',
        'attrs',
        'before',
        'content',
        'getAlias',
        'getId',
        'getIndex',
    ];

    /**
     * {@inheritDoc}
     *
     * @return PartialViewTemplate
     */
    public function make($name): ViewTemplateInterface
    {
        return new PartialViewTemplate($this, $name);
    }
}