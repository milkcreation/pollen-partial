<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Pagination;

use Pollen\Partial\Drivers\PaginationDriverInterface;
use Pollen\Partial\PartialViewLoader;
use RuntimeException;

class PaginationView extends PartialViewLoader
{
    /**
     * Récupération de l'instance de délégation.
     *
     * @return PaginationDriverInterface
     */
    protected function getDelegate(): PaginationDriverInterface
    {
        /** @var PaginationDriverInterface|object|null $delegate */
        $delegate = $this->engine->getDelegate();
        if ($delegate instanceof PaginationDriverInterface) {
            return $delegate;
        }

        throw new RuntimeException('MailableViewLoader must have a delegate Mailable instance');
    }

    /**
     * Récupération de la page courante.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->getDelegate()->query()->getCurrentPage();
    }

    /**
     * Récupération de la dernière page.
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->getDelegate()->query()->getLastPage();
    }
}