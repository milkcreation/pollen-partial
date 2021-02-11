<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Exception;
use Pollen\Partial\PartialDriverInterface;
use Symfony\Component\HttpFoundation\Response as Response;

interface DownloaderDriverInterface extends PartialDriverInterface
{
    /**
     * Récupération de l'url de requête HTTP.
     *
     * @param array ...$params Liste des paramètres optionnels de formatage de l'url.
     *
     * @return string
     */
    public function getUrl(...$params): string;

    /**
     * Définition de l'url de requête HTTP.
     *
     * @param string|null $url
     *
     * @return static
     */
    public function setUrl(?string $url = null): DownloaderDriverInterface;

    /**
     * Récupération du chemin absolu du fichier à téléchargé basé sur une liste d'arguments.
     *
     * @param array ...$args Liste des arguments de récupération du chemin absolu.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getFilename(...$args): string;

    /**
     * Controleur de traitement de la requête HTTP.
     *
     * @param string $path
     *
     * @return Response
     */
    public function getResponse(string $path): Response;
}