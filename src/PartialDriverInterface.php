<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\Http\JsonResponseInterface;
use Pollen\Http\RequestInterface;

/**
 * @mixin \Pollen\Support\Concerns\ParamsBagTrait
 * @mixin \Pollen\Support\ParamsBag
 */
interface PartialDriverInterface
{
    /**
     * Récupération des paramètres.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key);

    /**
     * Délégation d'appel des méthodes du ParamBag.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments);

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
     * Récupération de l'instance de la requête HTTP associée.
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;

    /**
     * Récupération de l'url de traitement des requêtes XHR.
     *
     * @param array $params
     *
     * @return string
     */
    public function getXhrUrl(array $params = []): string;

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
     * Récupération du gestionnaire.
     *
     * @return PartialManagerInterface
     */
    public function partialManager(): PartialManagerInterface;

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
     * Définition de l'instance de la requête HTTP associée.
     *
     * @param RequestInterface $request
     *
     * @return static
     */
    public function setRequest(RequestInterface $request): PartialDriverInterface;

    /**
     * Définition de l'instance du moteur d'affichage.
     *
     * @param PartialViewEngineInterface $viewEngine
     *
     * @return static
     */
    public function setViewEngine(PartialViewEngineInterface $viewEngine): PartialDriverInterface;

    /**
     * Instance du gestionnaire de gabarits d'affichage ou rendu du gabarit d'affichage.
     *
     * @param string|null view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return PartialViewEngineInterface|string
     */
    public function view(?string $view = null, array $data = []);

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
     * @return JsonResponseInterface
     */
    public function xhrResponse(...$args): JsonResponseInterface;
}