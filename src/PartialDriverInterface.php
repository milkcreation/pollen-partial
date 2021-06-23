<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\Http\ResponseInterface;
use Pollen\Support\Concerns\ParamsBagDelegateTraitInterface;
use Pollen\Support\Proxy\HttpRequestProxyInterface;
use Pollen\Support\Proxy\PartialProxyInterface;
use Pollen\Support\Proxy\ViewProxyInterface;
use Pollen\View\ViewInterface;

interface PartialDriverInterface extends
    HttpRequestProxyInterface,
    ParamsBagDelegateTraitInterface,
    PartialProxyInterface,
    ViewProxyInterface
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Post-affichage.
     *
     * @return void
     */
    public function after(): void;

    /**
     * Affichage de la liste des attributs de balise.
     *
     * @return void
     */
    public function attrs(): void;

    /**
     * Pré-affichage.
     *
     * @return void
     */
    public function before(): void;

    /**
     * Chargement.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Affichage du contenu.
     *
     * @return void
     */
    public function content(): void;

    /**
     * Récupération de l'identifiant de qualification dans le gestionnaire.
     *
     * @return string
     */
    public function getAlias(): string;

    /**
     * Récupération du préfixe de qualification de la classe associée.
     *
     * @return string
     */
    public function getBaseClass(): string;

    /**
     * Récupération de l'identifiant de qualification local.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Récupération de l'indice de qualification dans le gestionnaire.
     *
     * @return int
     */
    public function getIndex(): int;

    /**
     * Récupération de l'url de traitement des requêtes XHR.
     *
     * @param array $params
     * @param string|null $controller
     *
     * @return string
     */
    public function getXhrUrl(array $params = [], ?string $controller = null): string;

    /**
     * Traitement de l'attribut "class" de la balise HTML.
     *
     * @return static
     */
    public function parseAttrClass(): PartialDriverInterface;

    /**
     * Traitement de l'attribut "id" de la balise HTML.
     *
     * @return static
     */
    public function parseAttrId(): PartialDriverInterface;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition de l'alias de qualification.
     *
     * @param string $alias
     *
     * @return static
     */
    public function setAlias(string $alias): PartialDriverInterface;

    /**
     * Définition de la liste des paramètres par défaut.
     *
     * @param array $defaults
     *
     * @return void
     */
    public static function setDefaults(array $defaults = []): void;

    /**
     * Définition de l'identifiant de qualification.
     *
     * @param string $id
     *
     * @return static
     */
    public function setId(string $id): PartialDriverInterface;

    /**
     * Définition de l'indice de qualification.
     *
     * @param int $index
     *
     * @return static
     */
    public function setIndex(int $index): PartialDriverInterface;

    /**
     * Chemin absolu du répertoire des gabarits d'affichage.
     *
     * @return string
     */
    public function viewDirectory(): string;

    /**
     * Contrôleur de traitement des requêtes XHR.
     *
     * @param array ...$args
     *
     * @return ResponseInterface
     */
    public function xhrResponse(...$args): ResponseInterface;
}