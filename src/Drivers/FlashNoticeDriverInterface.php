<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriverInterface;

interface FlashNoticeDriverInterface extends PartialDriverInterface
{
    /**
     * Ajout d'une notification.
     *
     * @param string $message Intitulé du message de notification.
     * @param string $type Type de message. error|info|success|warning.
     * @param array $attrs Liste des attributs de personnalisation du message de notification.
     *
     * @return static
     */
    public function add(string $message, string $type = 'error', array $attrs = []): FlashNoticeDriverInterface;

    /**
     * Ajout d'une notification d'erreur.
     *
     * @param string $message Intitulé du message de notification.
     * @param array $attrs Liste des attributs de personnalisation du message de notification.
     *
     * @return static
     */
    public function error(string $message, array $attrs = []): FlashNoticeDriverInterface;

    /**
     * Ajout d'une notification d'information.
     *
     * @param string $message Intitulé du message de notification.
     * @param array $attrs Liste des attributs de personnalisation du message de notification.
     *
     * @return static
     */
    public function info(string $message, array $attrs = []): FlashNoticeDriverInterface;

    /**
     * Ajout d'une notification de succès.
     *
     * @param string $message Intitulé du message de notification.
     * @param array $attrs Liste des attributs de personnalisation du message de notification.
     *
     * @return static
     */
    public function success(string $message, array $attrs = []): FlashNoticeDriverInterface;
    /**
     * Ajout d'une notification d'alerte.
     *
     * @param string $message Intitulé du message de notification.
     * @param array $attrs Liste des attributs de personnalisation du message de notification.
     *
     * @return static
     */
    public function warning(string $message, array $attrs = []): FlashNoticeDriverInterface;
}