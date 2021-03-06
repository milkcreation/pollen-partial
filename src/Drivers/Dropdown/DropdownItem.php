<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Dropdown;

use Pollen\Support\ParamsBag;

class DropdownItem extends ParamsBag implements DropdownItemInterface
{
    /**
     * Nom de qualification de l'élément.
     * @var string
     */
    protected $name = '';

    /**
     * @param string $name Nom de qualification de l'élément.
     * @param string|array $attrs
     */
    public function __construct(string $name, $attrs)
    {
        $this->name = $name;

        if (!is_array($attrs)) {
            $attrs = ['content' => $attrs];
        }

        $this->set($attrs);
        $this->parse();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string) $this->getContent();
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        return $this->get('content');
    }
}