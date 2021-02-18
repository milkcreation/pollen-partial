<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Pagination;

use tiFy\Support\ParamsBag;
use tiFy\Support\Concerns\PaginationAwareTrait;

class PaginationQuery extends ParamsBag implements PaginationQueryInterface
{
    use PaginationAwareTrait;

    /**
     * @param array|object|null $args
     */
    public function __construct($args = null)
    {
        if (is_array($args)) {
            $this->set($args);
        } elseif (is_object($args)) {
            if (($traits = class_uses($args)) && in_array(PaginationAwareTrait::class, $traits)) {
                $this->set($args->toArray());
            } else {
                $this->set(get_object_vars($args));
            }
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return PaginationQueryInterface
     */
    public function parse(): PaginationQueryInterface
    {
        parent::parse();

        if ($baseUrl = $this->pull('base_url')) {
            $this->setBaseUrl($baseUrl);
        }

        if ($count = $this->pull('count')) {
            $this->setCount($count);
        }

        if ($currentPage = $this->pull('current_page')) {
            $this->setCurrentPage($currentPage);
        }

        if ($lastPage = $this->pull('last_page')) {
            $this->setLastPage($lastPage);
        }

        if ($pageIndex = $this->pull('page_index')) {
            $this->setPageIndex($pageIndex);
        }

        if ($per_page = $this->pull('per_page')) {
            $this->setPerPage($per_page);
        }

        if ($segmentUrl = $this->pull('segment_url')) {
            $this->setSegmentUrl($segmentUrl);
        }

        if ($total = $this->pull('total')) {
            $this->setTotal($total);
        }

        if ($offset = $this->pull('offset')) {
            $this->setOffset($offset);
        }

        return $this;
    }
}