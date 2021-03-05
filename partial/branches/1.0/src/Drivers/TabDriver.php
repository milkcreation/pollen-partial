<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Exception;
use Pollen\Http\JsonResponse;
use Pollen\Http\ResponseInterface;
use Pollen\Partial\Drivers\Tab\TabCollection;
use Pollen\Partial\Drivers\Tab\TabCollectionInterface;
use Pollen\Partial\Drivers\Tab\TabViewLoader;
use Pollen\Partial\PartialDriver;
use Pollen\Support\Proxy\SessionProxy;

class TabDriver extends PartialDriver implements TabDriverInterface
{
    use SessionProxy;

    /**
     * Collection des éléments déclaré.
     * @var TabCollectionInterface
     */
    private $tabCollection;

    /**
     * @inheritDoc
     */
    public function addItem($def): TabDriverInterface
    {
        $this->getTabCollection()->add($def);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            /**
             * @var string|null $active Nom de qualification de l'élément actif.
             */
            'active'   => null,
            /**
             * @var array $items {
             * Liste des onglets de navigation.
             * @type string $name Nom de qualification.
             * @type string $parent Nom de qualification de l'élément parent.
             * @type string|callable $content
             * @type int $position Ordre d'affichage dans le
             * }
             */
            'items'    => [],
            /**
             * @var array $rotation Rotation des styles d'onglet. left|top|default|pills.
             */
            'rotation' => [],
            /**
             * Activation du traitement de la requête HTML XHR
             */
            'ajax'     => true,
        ]);
    }

    /**
     * Récupération de l'élément actif.
     *
     * @return string
     */
    protected function getActive(): string
    {
        if (!$active = $this->get('active')) {
            $sessionName = md5(Url::current()->path() . $this->getId());
            if ($this->get('ajax') && ($store = $this->session()->registerStore($sessionName))) {
                $active = $store->get('active', '');
                $this->set('attrs.data-options.ajax.data.session', $sessionName);
            }
        }

        return $active;
    }

    /**
     * @inheritDoc
     */
    public function getTabCollection(): TabCollectionInterface
    {
        if (is_null($this->tabCollection)) {
            $this->tabCollection = (new TabCollection([]))->setTabDriver($this);
        }

        return $this->tabCollection;
    }

    /**
     * @inheritDoc
     */
    public function getTabStyle(int $depth = 0): string
    {
        return $this->get("rotation.{$depth}") ?: 'default';
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        $items = $this->pull('items', []);

        if ($items instanceof TabCollectionInterface) {
            $this->setTabCollection($items);
        } elseif (is_array($items)) {
            foreach ($items as $item) {
                $this->addItem($item);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if ($ajax = $this->get('ajax', false)) {
            $defaultsAjax = [
                'data'     => [],
                'dataType' => 'json',
                'method'   => 'post',
                'url'      => $this->partial()->getXhrRouteUrl('tab'),
            ];
            $this->set('attrs.data-options.ajax', is_array($ajax) ? array_merge($defaultsAjax, $ajax) : $defaultsAjax);
        }

        $this->set([
            'attrs.data-control' => 'tab',
            'attrs.data-options.active' => $this->getActive() ?: ''
        ]);

        try {
            $items = $this->getTabCollection()->boot()->getGrouped();
        } catch (Exception $e) {
            return $e->getMessage();
        }
        $this->set(compact('items'));

        return $this->view('index', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function setTabCollection(TabCollectionInterface $tabCollection): TabDriverInterface
    {
        $this->tabCollection = $tabCollection->setTabDriver($this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function view(?string $view = null, $data = [])
    {
        if ($this->viewEngine === null) {
            $viewEngine = parent::view();
            $viewEngine->setLoader(TabViewLoader::class);
            $this->viewEngine->setDelegateMixin('getTabStyle');
        }

        return parent::view($view, $data);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources('/views/tab');
    }

    /**
     * @inheritDoc
     */
    public function xhrResponse(...$args): ResponseInterface
    {
        if (($sessionName = Request::input('session')) && $store = Session::registerStore($sessionName)) {
            $store->put('active', Request::input('active'));

            return new JsonResponse(['success' => true]);
        }
        return new JsonResponse(['success' => false]);
    }
}