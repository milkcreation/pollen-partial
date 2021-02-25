<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\View\ViewTemplateInterface;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 */
interface PartialViewTemplateInterface extends ViewTemplateInterface
{
    /**
     * Rendu d'une portion d'affichage.
     *
     * @param string|null $alias Alias de qualification.
     * @param mixed $idOrParams Identifiant de qualification|Liste des attributs de configuration.
     * @param array $params Liste des attributs de configuration.
     *
     * @return string
     */
    public function partial(string $alias, $idOrParams = null, array $params = []): string;
}
