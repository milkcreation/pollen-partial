<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Dropdown;

use ArrayIterator;
use Illuminate\Support\Collection as IlluminateCollection;
use Pollen\Partial\Drivers\DropdownDriverInterface;

class DropdownCollection implements DropdownCollectionInterface
{
    /**
     * Instance du controleur d'affichage associé.
     * @var DropdownDriverInterface
     */
    protected $partial;

    /**
     * Liste des éléments.
     * @var DropdownItem[]|array
     */
    protected $items = [];

    /**
     * Récupération de l'iteration courante.
     * @var ArrayIterator
     */
    protected $_iteration;

    /**
     * @inheritDoc
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @inheritDoc
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @inheritDoc
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @param array $items Liste des éléments.
     */
    public function __construct(array $items)
    {
        array_walk($items, [$this, 'walk']);
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function chunk(int $size): iterable
    {
        return $this->collect()->chunk($size);
    }

    /**
     * @inheritDoc
     */
    public function clear(): DropdownCollectionInterface
    {
        $this->items = [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function collect($items = null)
    {
        return is_null($items) ? new IlluminateCollection($this->items) : new IlluminateCollection($items);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->getIteration()->current();
    }

    /**
     * @inheritDoc
     */
    public function exists()
    {
        return !empty($this->items);
    }

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->_iteration = new ArrayIterator($this->items);
    }

    /**
     * @inheritDoc
     */
    public function getIteration()
    {
        return ($this->_iteration instanceof ArrayIterator) ? $this->_iteration : $this->getIterator();
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->getIteration()->key();
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) :
            $this->items[] = $value;
        else :
            $this->items[$key] = $value;
        endif;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * @inheritDoc
     */
    public function pluck($value, $key = null)
    {
        return $this->collect()->pluck($value, $key)->all();
    }

    /**
     * @inheritDoc
     */
    public function setPartial(DropdownDriverInterface $partial): DropdownCollectionInterface
    {
        $this->partial = $partial;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function walk($item, $key = null): DropdownItemInterface
    {
        if(!$item instanceof DropdownItemInterface) {
            $item = new DropdownItem((string)$key, $item);
        }

        return $this->items[$key] = $item;
    }
}