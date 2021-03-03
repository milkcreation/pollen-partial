<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Closure;
use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialDriverInterface;

class NoticeDriver extends PartialDriver implements NoticeDriverInterface
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            /**
             * @var string|array|callable $content Texte du message de notification. défaut 'Lorem ipsum dolor site amet'.
             */
            'content' => 'Lorem ipsum dolor site amet',
            /**
             * @var bool $dismiss Affichage du bouton de masquage de la notification.
             */
            'dismiss' => false,
            /**
             * @var int $timeout Délai d'expiration d'affichage du message. Exprimé en millisecondes.
             */
            'timeout' => 0,
            /**
             * @var string $type Type de notification info|warning|success|error. défaut info.
             */
            'type'    => 'info',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        $this->set('attrs.data-control', 'notice');
        $this->set('attrs.data-timeout', $this->get('timeout', 0));

        $this->set(
            'content',
            ($content = $this->get('content')) instanceof Closure ? call_user_func($content) : $content
        );

        if ($dismiss = $this->get('dismiss')) {
            if (!is_array($dismiss)) {
                $dismiss = [];
            }

            $this->set('dismiss', $this->partial()->get('tag', array_merge([
                'tag'     => 'button',
                'attrs'   => [
                    'data-toggle' => 'notice.dismiss',
                ],
                'content' => '&times;',
            ], $dismiss)));
        } else {
            $this->set('dismiss', '');
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parseAttrClass(): PartialDriverInterface
    {
        $base = ucfirst(preg_replace('/\./', '-', $this->getAlias()));

        $default_class = "{$base} {$base}--" . $this->getIndex() . " {$base}--" . $this->get('type');
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class'), $default_class));
        }

        if (!$this->get('attrs.class')) {
            $this->forget('attrs.class');
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/notice");
    }
}