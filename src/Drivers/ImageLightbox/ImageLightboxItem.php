<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers\ImageLightbox;

use Pollen\Support\Html;
use Pollen\Support\ParamsBag;

class ImageLightboxItem extends ParamsBag implements ImageLightboxItemInterface
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'caption' => '',
            'content' => null,
            'group'   => '',
            'src'     => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAttrs(bool $linearize = true)
    {
        return $linearize ? Html::attr($this->get('attrs', [])) : $this->get('attrs', []);
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        $thumbnail = $this->get('content');

        if (is_null($thumbnail)) {
            return (string)partial('tag', [
                'tag'   => 'img',
                'attrs' => [
                    'src' => $this->get('src'),
                    'alt' => basename($this->get('src')),
                ],
            ]);
        }

        return is_callable($thumbnail) ? call_user_func($thumbnail) : (string)$thumbnail;
    }

    /**
     * @inheritDoc
     */
    public function getGroup(): ?string
    {
        return $this->get('group');
    }

    /**
     * @inheritDoc
     */
    public function parse(): ImageLightboxItemInterface
    {
        parent::parse();

        if ($caption = $this->pull('caption')) {
            $this->set('attrs.data-ilb2-caption', $caption);
        }

        if (!$this->get('attrs.href')) {
            $this->set('attrs.href', $this->get('src') ?: '#');
        }

        if (!$this->has('attrs.title')) {
            $this->set('attrs.title', basename($this->get('src')));
        }

        if ($group = $this->get('group')) {
            $this->set('attrs.data-group', $group);
        }

        $this->set('attrs.data-control', 'image-lightbox.item');

        return $this;
    }
}