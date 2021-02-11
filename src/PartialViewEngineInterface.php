<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\View\ViewEngineInterface;
use Pollen\View\ViewTemplateInterface;

interface PartialViewEngineInterface extends ViewEngineInterface
{
    /**
     * {@inheritDoc}
     *
     * @return PartialViewTemplate
     */
    public function make($name): ViewTemplateInterface;
}