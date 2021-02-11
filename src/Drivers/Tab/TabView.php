<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Tab;

use Pollen\Partial\PartialView;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 * @method string getTabStyle(int $depth = 0)
 */
class TabView extends PartialView
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [
        'after',
        'attrs',
        'before',
        'content',
        'getAlias',
        'getId',
        'getIndex',
        'getTabStyle'
    ];
}