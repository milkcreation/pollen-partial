<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Pagination;

use tiFy\Contracts\Support\ParamsBag;

/**
 * @mixin \tiFy\Support\Concerns\PaginationAwareTrait
 */
interface PaginationQueryInterface extends ParamsBag
{
}