<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\View\PartialAwareViewLoaderInterface;
use Pollen\View\ViewLoaderInterface;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 */
interface PartialViewLoaderInterface extends PartialAwareViewLoaderInterface, ViewLoaderInterface
{
}