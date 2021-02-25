<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\View\ViewTemplate;
use RuntimeException;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 */
class PartialViewTemplate extends ViewTemplate implements PartialViewTemplateInterface
{
    /**
     * Récupération de l'instance de délégation.
     *
     * @return PartialDriverInterface
     */
    protected function getDelegate(): PartialDriverInterface
    {
        /** @var PartialDriverInterface|object|null $delegate */
        $delegate = $this->engine->getDelegate();
        if ($delegate instanceof PartialDriverInterface) {
            return $delegate;
        }

        throw new RuntimeException('FieldViewTemplate must have a delegate FieldDriver instance');
    }

    /**
     * @inheritDoc
     */
    public function partial(string $alias, $idOrParams = null, array $params = []): string
    {
        $manager = $this->getDelegate()->partialManager();

        return (string)$manager->get($alias, $idOrParams, $params);
    }
}