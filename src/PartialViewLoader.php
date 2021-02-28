<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\View\PartialAwareViewLoader;
use Pollen\View\ViewLoader;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 */
class PartialViewLoader extends ViewLoader implements PartialViewLoaderInterface
{
    use PartialAwareViewLoader;
}