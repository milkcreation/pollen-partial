<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialDriverInterface;
use tiFy\Support\Proxy\Session;

class FlashNoticeDriver extends PartialDriver implements FlashNoticeDriverInterface
{
    /**
     * @inheritDoc
     */
    public function add(string $message, string $type = 'error', array $attrs = []): FlashNoticeDriverInterface
    {
        $key = ($namespace = $this->get('namespace')) ? "{$namespace}-{$type}" : $type;

        Session::flash([$key => compact('attrs', 'message', 'type')]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            'namespace' => '',
            'types'     => ['error', 'info', 'success', 'warning'],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, array $attrs = []): FlashNoticeDriverInterface
    {
        return $this->add($message, 'error', $attrs);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, array $attrs = []): FlashNoticeDriverInterface
    {
        return $this->add($message, 'info', $attrs);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        if ($namespace = $this->get('namespace')) {
            $types = $this->get('types');
            foreach ($types as &$type) {
                $type = "{$namespace}-{$type}";
            }
            $this->set(compact('types'));
        }
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        foreach ($this->get('types') as $type) {
            if (!$this->has($type)) {
                $this->set($type, Session::flash($type));
            }
        }

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function success(string $message, array $attrs = []): FlashNoticeDriverInterface
    {
        return $this->add($message, 'success', $attrs);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, array $attrs = []): FlashNoticeDriverInterface
    {
        return $this->add($message, 'warning', $attrs);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/flash-notice");
    }
}