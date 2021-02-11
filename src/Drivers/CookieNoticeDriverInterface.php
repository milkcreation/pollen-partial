<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use tiFy\Contracts\Cookie\Cookie;
use Pollen\Partial\PartialDriverInterface;

interface CookieNoticeDriverInterface extends PartialDriverInterface
{
    /**
     * Récupération de l'instance du cookie associé.
     * 
     * @return Cookie
     */
    public function cookie(): Cookie;

    /**
     * Élement de validation du cookie.
     *
     * @param array $args Liste des attributs de configuration.
     *
     * @return string
     */
    public function trigger($args = []): string;
}