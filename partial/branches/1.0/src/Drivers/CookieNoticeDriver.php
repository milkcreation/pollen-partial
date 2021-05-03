<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Closure;
use Exception;
use Pollen\Cookie\CookieInterface;
use Pollen\Http\JsonResponse;
use Pollen\Http\ResponseInterface;
use Pollen\Partial\PartialDriver;
use Pollen\Support\Proxy\CookieProxy;

class CookieNoticeDriver extends PartialDriver implements CookieNoticeDriverInterface
{
    use CookieProxy;

    /**
     * Instance du cookie associé.
     * @var CookieInterface|null
     */
    protected $cookie;

    /**
     * @inheritDoc
     */
    public function getCookie(): CookieInterface
    {
        if (is_null($this->cookie)) {
            $this->cookie = $this->cookie()->make($this->getId(), array_merge($this->get('cookie', [])));
        }

        return $this->cookie;
    }

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            /**
             * @var string|callable $content Texte du message de notification. défaut 'Lorem ipsum dolor site amet'.
             */
            'content' => '<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>',
            /**
             * @var array $cookie Liste des paramètre de cookie.
             */
            'cookie'  => [],
            /**
             * @var bool $dismiss Affichage du bouton de masquage de la notification.
             */
            'dismiss' => false,
            /**
             * @var string $type Type de notification info|warning|success|error. défaut info.
             */
            'type'    => 'info',
            /**
             * @var array $trigger Attribut de configuration du lien de validation et de création du cookie.
             */
            'trigger' => [],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $content = $this->get('content');
        $this->set('content', $content instanceof Closure ? call_user_func($content) : $content);

        if ($this->getCookie()->httpValue()) {
            $this->set('attrs.aria-hidden', 'true');
        }

        $this->set('attrs.data-options', [
            'ajax' => [
                'url'    => $this->partial()->getXhrRouteUrl('cookie-notice'),
                'data'   => [
                    '_id'     => $this->getId(),
                    '_cookie' => $this->get('cookie', []),
                ],
                'method' => 'POST',
            ],
        ]);

        if ($trigger = $this->get('trigger', [])) {
            $this->set('content', $this->get('content') . $this->trigger(is_array($trigger) ?: []));
        }

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function trigger($args = []): string
    {
        $args = array_merge([
            'tag'     => 'a',
            'attrs'   => [],
            'content' => __('Fermer', 'tify'),
        ], $args);

        if (($args['tag'] === 'a') && !isset($args['attrs']['href'])) {
            $args['attrs']['href'] = '#';
        }

        $args['attrs']['data-toggle'] = 'notice.trigger';

        return $this->partial()->get('tag', $args)->render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/cookie-notice");
    }

    /**
     * @inheritDoc
     */
    public function xhrResponse(...$args): ResponseInterface
    {
        $id = $this->httpRequest()->input('_id') ?: 'test';

        try {
            $cookie = $this->cookie()->make(
                $id, array_merge($this->httpRequest()->input('_cookie', []), ['value' => '1'])
            );

            return new JsonResponse([
                'success' => true,
                'data' => $cookie->getName()
            ]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'data' => $e->getMessage()]);
        }
    }
}