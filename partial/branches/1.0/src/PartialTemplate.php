<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\View\Engines\Plates\PlatesPartialAwareTemplateTrait;
use Pollen\View\Engines\Plates\PlatesTemplate;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 */
class PartialTemplate extends PlatesTemplate
{
    use PlatesPartialAwareTemplateTrait;
}