<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\Tab;

use Closure;
use Pollen\Support\Concerns\BootableTrait;
use Pollen\Support\Concerns\BuildableTrait;
use Pollen\Support\Html;
use Pollen\Support\ParamsBag;
use RuntimeException;

class TabFactory extends ParamsBag implements TabFactoryInterface
{
    use BootableTrait;
    use BuildableTrait;

    /**
     * Instance du gestionnaire d'éléments.
     * @var TabCollectionInterface
     */
    protected $collection;

    /**
     * Identifiant de qualification.
     * @var string
     */
    private $id;

    /**
     * Identifiant d'indexation.
     * @var int
     */
    private $index = 0;

    /**
     * Niveau de profondeur dans l'interface d'affichage.
     * @var int
     */
    protected $depth = 0;

    /**
     * Instance de l'élément parent.
     * @var TabFactoryInterface|null
     */
    protected $parent;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * Génération des identifiants de qualification.
     *
     * @return void
     */
    private function generateIds(): void
    {
        if (!$tabDriver = $this->collection()->tabDriver()) {
            throw new RuntimeException('Tab factory generation id failed');
        }

        $this->index = $this->collection()->getIncreasedItemIdx();
        $this->id = "tab-{$tabDriver->getIndex()}--{$this->index}";

        $name = $this->get('name');
        if (!$name || !is_string($name)) {
            $this->set('name', $this->id);
        }
    }

    /**
     * @inheritDoc
     */
    public function boot(): TabFactoryInterface
    {
        if (!$this->isBooted()) {
            $this->parse();

            $this->setBooted();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build(): TabFactoryInterface
    {
        if (!$this->isBuilt()) {
            try {
                $this->generateIds();
            } catch (RuntimeException $e) {
                throw $e;
            }

            $this->setBuilt();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function collection(): TabCollectionInterface
    {
        return $this->collection;
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'content'  => '',
            'name'     => '',
            'parent'   => null,
            'position' => null,
            'title'    => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function getChildren(): iterable
    {
        return $this->collection()->getGrouped($this->getName());
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        $content = $this->get('content');

        return $content instanceof Closure ? $content() : (string)$content;
    }

    /**
     * @inheritDoc
     */
    public function getContentAttrs(bool $linearized = true): string
    {
        $attr = [
            'id'           => $this->getId(),
            'class'        => 'Tab-contentPane',
            'data-name'    => $this->getName(),
            'data-control' => 'tab.content.pane'
        ];
        return Html::attr($attr);
    }

    /**
     * @inheritDoc
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->get('name');
    }

    /**
     * @inheritDoc
     */
    public function getNavAttrs(): string
    {
        $attr = [
            'class'         => 'Tab-navLink',
            'data-control'  => 'tab.nav.link',
            'data-name'     => $this->getName(),
            'href'          => "#{$this->getId()}",
        ];
        return Html::attr($attr);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?TabFactoryInterface
    {
        if (is_null($this->parent)) {
            if ($name = $this->get('parent')) {
                $this->parent = $this->collection()->get($name) ?: false;
            } else {
                $this->parent = false;
            }
        }
        return $this->parent ?: null;
    }

    /**
     * @inheritDoc
     */
    public function getParentName(): string
    {
        return ($parent = $this->getParent()) instanceof TabFactoryInterface ? $parent->getName() : '';
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return (string)$this->get('title');
    }

    /**
     * @inheritDoc
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * @inheritDoc
     */
    public function isBuilt(): bool
    {
        return $this->built;
    }

    /**
     * @inheritDoc
     */
    public function setCollection(TabCollectionInterface $collection): TabFactoryInterface
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDepth(int $depth = 0): TabFactoryInterface
    {
        $this->depth = $depth;

        return $this;
    }
}