<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Closure;
use League\Route\Http\Exception\NotFoundException;
use Pollen\Http\ResponseInterface;
use Pollen\Support\Concerns\BootableTraitInterface;
use Pollen\Support\Concerns\ConfigBagAwareTraitInterface;
use Pollen\Support\Proxy\ContainerProxyInterface;
use Pollen\Support\Proxy\RouterProxyInterface;

interface PartialManagerInterface extends
    BootableTraitInterface,
    ConfigBagAwareTraitInterface,
    ContainerProxyInterface,
    RouterProxyInterface
{
    /**
     * Récupération de la liste des pilote déclarés.
     *
     * @return PartialDriverInterface[][]
     */
    public function all(): array;

    /**
     * Chargement.
     *
     * @return static
     */
    public function boot(): PartialManagerInterface;

    /**
     * Récupération d'une portion d'affichage déclarée.
     *
     * @param string $alias Alias de qualification.
     * @param string|array|null $idOrParams Identifiant de qualification ou paramètres de configuration.
     * @param array|null $params Liste des paramètres de configuration.
     *
     * @return PartialDriverInterface|null
     */
    public function get(string $alias, $idOrParams = null, ?array $params = []): ?PartialDriverInterface;

    /**
     * Récupération de l'url de traitement des requêtes XHR.
     *
     * @param string $partial Alias de qualification du pilote associé.
     * @param string|null $controller Nom de qualification du controleur de traitement de la requête XHR.
     * @param array $params Liste de paramètres complémentaire transmis dans l'url
     *
     * @return string|null
     */
    public function getXhrRouteUrl(string $partial, ?string $controller = null, array $params = []): ?string;

    /**
     * Déclaration d'un pilote.
     *
     * @param string $alias
     * @param string|PartialDriverInterface|Closure $driverDefinition
     * @param Closure|null $registerCallback
     *
     * @return static
     */
    public function register(string $alias, $driverDefinition, ?Closure $registerCallback = null): PartialManagerInterface;

    /**
     * Déclaration des instances de pilotes par défaut.
     *
     * @return static
     */
    public function registerDefaultDrivers(): PartialManagerInterface;

    /**
     * Chemin absolu vers une ressources (fichier|répertoire).
     *
     * @param string|null $path Chemin relatif vers la ressource.
     *
     * @return string
     */
    public function resources(?string $path = null): string;

    /**
     * Définition du chemin absolu vers le répertoire des ressources.
     *
     * @var string $resourceBaseDir
     *
     * @return static
     */
    public function setResourcesBaseDir(string $resourceBaseDir): PartialManagerInterface;

    /**
     * Répartiteur de traitement d'une requête XHR.
     *
     * @param string $partial Alias de qualification du pilote associé.
     * @param string $controller Nom de qualification du controleur de traitement de la requête.
     * @param mixed ...$args Liste des arguments passés au controleur
     *
     * @return ResponseInterface
     *
     * @throws NotFoundException
     */
    public function xhrResponseDispatcher(string $partial, string $controller, ...$args): ResponseInterface;
}