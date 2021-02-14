<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Closure;
use League\Route\Http\Exception\NotFoundException;
use Pollen\Routing\RouterInterface;

/**
 * @mixin \Pollen\Support\Concerns\BootableTrait
 * @mixin \Pollen\Support\Concerns\ConfigBagTrait
 * @mixin \Pollen\Support\Concerns\ContainerAwareTrait
 */
interface PartialInterface
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
    public function boot(): PartialInterface;

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
     * Récupération de l'instance du gestionnaire de routage.
     *
     * @return RouterInterface|null
     */
    public function getRouter(): ?RouterInterface;

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
     * @param Closure|null $callback
     *
     * @return static
     */
    public function register(string $alias, $driverDefinition, ?Closure $callback = null): PartialInterface;

    /**
     * Déclaration des instances de pilotes par défaut.
     *
     * @return static
     */
    public function registerDefaultDrivers(): PartialInterface;

    /**
     * Définition de l'instance du gestionnaire de routage.
     *
     * @param RouterInterface $router
     *
     * @return PartialInterface
     */
    public function setRouter(RouterInterface $router): PartialInterface;

    /**
     * Répartiteur de traitement d'une requête XHR.
     *
     * @param string $partial Alias de qualification du pilote associé.
     * @param string $controller Nom de qualification du controleur de traitement de la requête.
     * @param mixed ...$args Liste des arguments passés au controleur
     *
     * @return array
     *
     * @throws NotFoundException
     */
    public function xhrResponseDispatcher(string $partial, string $controller, ...$args): array;
}