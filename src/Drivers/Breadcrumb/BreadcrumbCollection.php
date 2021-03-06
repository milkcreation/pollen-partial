<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Breadcrumb;

use Illuminate\Support\Collection;
use Pollen\Partial\Drivers\BreadcrumbDriverInterface;
use Pollen\Support\Proxy\PartialProxy;

class BreadcrumbCollection implements BreadcrumbCollectionInterface
{
    use PartialProxy;

    /**
     * Liste des éléments déclarés.
     * @var array
     */
    protected $items = [];

    /**
     * Instance du pilote de fil d'ariane.
     * @var BreadcrumbDriverInterface
     */
    protected $manager;

    /**
     * Liste des portions du fil d'ariane.
     * @var array
     */
    protected $parts = [];

    /**
     * Indicateur de pré-récupération des éléments.
     * @var bool
     */
    protected $prefetched = false;

    /**
     * @param BreadcrumbDriverInterface $manager Instance du pilote de fil d'ariane.
     */
    public function __construct(BreadcrumbDriverInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function add(string $render, ?int $position = null, array $wrapper = []): int
    {
        if (is_null($position)) {
            $position = count($this->items);
        } elseif (isset($this->items[$position])) {
            $items = [];
            (new Collection($this->items))
                ->sortKeysDesc()
                ->each(function (array $item, $key) use (&$items, $position) {
                    if ($key >= $position) {
                        $key++;
                    }
                    $items[$key] = $item;
                });
            $this->items = $items;
        }

        $this->items[$position] = compact('render', 'wrapper');

        return $position;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        ksort($this->items, SORT_REGULAR);

        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function clear(?int $position = null): BreadcrumbCollectionInterface
    {
        if (is_null($position)) {
            $this->items = [];
        } else {
            unset($this->items[$position]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fetch(): array
    {
        $this->parts = (new Collection($this->items))->sortKeys()->map(function ($item) {
            return $this->parse($item);
        })->values()->all();

        return $this->parts;
    }

    /**
     * @inheritDoc
     */
    public function get(int $position): ?array
    {
        return $this->items[$position] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getRender(string $content, ?string $url = null, array $attrs = []): string
    {
        $attrs['class'] = sprintf($attrs['class'] ?? '%s', 'Breadcrumb-itemContent');

        if ($url) {
            $render = $this->partial()->get('tag', [
                'attrs'   => array_merge($attrs, [
                    'href' => $url,
                ]),
                'content' => $content,
                'tag'     => 'a',
            ])->render();
        } else {
            $render = $this->partial()->get('tag', [
                'attrs'   => $attrs,
                'content' => $content,
                'tag'     => 'span',
            ])->render();
        }

        return $render;
    }

    /**
     * @inheritDoc
     */
    public function getUrl($url, ?string $default = '#'): ?string
    {
       if (is_string($url)) {
           return $url;
       }
       return $url ? $default : null;
    }

    /**
     * @inheritDoc
     */
    public function has(int $position): bool
    {
        return isset($this->items[$position]);
    }

    /**
     * @inheritDoc
     */
    public function manager(): BreadcrumbDriverInterface
    {
        return $this->manager;
    }

    /**
     * @inheritDoc
     */
    public function move(int $from, int $to): ?int
    {
        if ($item = $this->get($from)) {
            $this->clear($from);

            if ($exists = $this->get($to)) {
                $this->clear($to)->add($exists['render'], $from, $exists['wrapper']);
            }

            return $this->add($item['render'], $to, $item['wrapper']);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function parse(array $item): string
    {
        $wrapper = $item['wrapper'] ?? [];
        $wrapper['attrs']['class'] = sprintf($wrapper['attrs']['class'] ?? '%s', 'Breadcrumb-item');

        $tag = array_merge([
            'tag' => 'li',
        ], $wrapper, ['content' => $item['render'] ?? '']);

        return $this->partial()->get('tag', $tag)->render();
    }

    /**
     * @inheritDoc
     */
    public function prefetch(): BreadcrumbCollectionInterface
    {
        if (!$this->prefetched) {
            events()->trigger('partial.breadcrumb.prefetch', [&$this]);
            $this->prefetched = true;
        }

        return $this;
    }
}