<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Cookie\CookieInterface;
use Pollen\Partial\PartialDriverInterface;

interface CookieNoticeDriverInterface extends PartialDriverInterface
{
    /**
     * Récupération de l'instance du cookie associé.
     * 
     * @return CookieInterface
     */
    public function getCookie(): CookieInterface;

    /**
     * Élement de validation du cookie.
     *
     * @param array $args Liste des attributs de configuration.
     *
     * @return string
     */
    public function trigger($args = []): string;
}