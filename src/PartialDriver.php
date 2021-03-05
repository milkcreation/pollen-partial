<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Closure;
use InvalidArgumentException;
use Pollen\Http\JsonResponse;
use Pollen\Http\ResponseInterface;
use Pollen\Support\Concerns\BootableTrait;
use Pollen\Support\Concerns\ParamsBagDelegateTrait;
use Pollen\Support\Proxy\HttpRequestProxy;
use Pollen\Support\HtmlAttrs;
use Pollen\Support\Proxy\PartialProxy;
use Pollen\Support\Str;
use Pollen\View\ViewEngine;
use Pollen\View\ViewEngineInterface;

abstract class PartialDriver implements PartialDriverInterface
{
    use BootableTrait;
    use HttpRequestProxy;
    use ParamsBagDelegateTrait;
    use PartialProxy;

    /**
     * Indice de l'instance dans le gestionnaire.
     * @var int
     */
    private $index = 0;

    /**
     * Alias de qualification.
     * @var string
     */
    protected $alias = '';

    /**
     * Liste des attributs par défaut.
     * @var array
     */
    protected static $defaults = [];

    /**
     * Identifiant de qualification.
     * {@internal par défaut concaténation de l'alias et de l'indice.}
     * @var string
     */
    protected $id = '';

    /**
     * Instance du moteur de gabarits d'affichage.
     * @var ViewEngineInterface
     */
    protected $viewEngine;

    /**
     * @param PartialManagerInterface $partialManager
     */
    public function __construct(PartialManagerInterface $partialManager)
    {
        $this->setPartialManager($partialManager);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function after(): void
    {
        echo ($after = $this->get('after')) instanceof Closure ? $after($this) : $after;
    }

    /**
     * @inheritDoc
     */
    public function attrs(): void
    {
        echo HtmlAttrs::createFromAttrs($this->get('attrs', []));
    }

    /**
     * @inheritDoc
     */
    public function before(): void
    {
        echo ($before = $this->get('before')) instanceof Closure ? $before($this) : $before;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if (!$this->isBooted()) {
            //events()->trigger('partial.driver.booting', [$this->getAlias(), $this]);

            $this->parseParams();

            $this->setBooted();

            //events()->trigger('partial.driver.booted', [$this->getAlias(), $this]);
        }
    }

    /**
     * @inheritDoc
     */
    public function content(): void
    {
        echo ($content = $this->get('content')) instanceof Closure ? $content($this) : $content;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function defaultParams(): array
    {
        return array_merge(
            static::$defaults,
            [
                /**
                 * @var array $attrs Attributs HTML du conteneur.
                 */
                'attrs'  => [],
                /**
                 * @var string $after Contenu placé après le conteneur.
                 */
                'after'  => '',
                /**
                 * @var string $before Contenu placé avant le conteneur.
                 */
                'before' => '',
                /**
                 * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
                 */
                'viewer' => [],
                /**
                 * @var Closure|array|string|null
                 */
                'render' => null,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @inheritDoc
     */
    public function getBaseClass(): string
    {
        return Str::studly($this->getAlias());
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
    public function getXhrUrl(array $params = [], ?string $controller = null): string
    {
        return $this->partial()->getXhrRouteUrl($this->getAlias(), $controller, $params);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        $this->parseAttrId()->parseAttrClass();
    }

    /**
     * @inheritDoc
     */
    public function parseAttrClass(): PartialDriverInterface
    {
        $base = $this->getBaseClass();

        $default_class = "{$base} {$base}--" . $this->getIndex();
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
    public function parseAttrId(): PartialDriverInterface
    {
        if (!$this->get('attrs.id')) {
            $this->forget('attrs.id');
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->view('index', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function setAlias(string $alias): PartialDriverInterface
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function setDefaults(array $defaults = []): void
    {
        static::$defaults = $defaults;
    }

    /**
     * @inheritDoc
     */
    public function setId(string $id): PartialDriverInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setIndex(int $index): PartialDriverInterface
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setViewEngine(ViewEngineInterface $viewEngine): PartialDriverInterface
    {
        $this->viewEngine = $viewEngine;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function view(?string $view = null, array $data = [])
    {
        if (is_null($this->viewEngine)) {
            $directory = null;
            $overrideDir = null;
            $default = $this->partial()->config('default.driver.viewer', []);

            $directory = $this->get('viewer.directory');
            if ($directory && !file_exists($directory)) {
                $directory = null;
            }

            $overrideDir = $this->get('viewer.override_dir');
            if ($overrideDir && !file_exists($overrideDir)) {
                $overrideDir = null;
            }

            if ($directory === null && isset($default['directory'])) {
                $default['directory'] = rtrim($default['directory'], '/') . '/' . $this->getAlias();
                if (file_exists($default['directory'])) {
                    $directory = $default['directory'];
                }
            }

            if ($overrideDir === null && isset($default['override_dir'])) {
                $default['override_dir'] = rtrim($default['override_dir'], '/') . '/' . $this->getAlias();
                if (file_exists($default['override_dir'])) {
                    $overrideDir = $default['override_dir'];
                }
            }

            if ($directory === null) {
                $directory = $this->viewDirectory();
                if (!file_exists($directory)) {
                    throw new InvalidArgumentException(
                        sprintf('Partial [%s] must have an accessible view directory', $this->getAlias())
                    );
                }
            }

            $this->viewEngine = new ViewEngine();
            if ($container = $this->partial()->getContainer()) {
                $this->viewEngine->setContainer($container);
            }

            $this->viewEngine->setDirectory($directory)->setDelegate($this)->setLoader(PartialViewLoader::class);

            if ($overrideDir !== null) {
                $this->viewEngine->addFolder('_override_dir', $overrideDir, true);
            }

            $mixins = [
                'after',
                'attrs',
                'before',
                'content',
                'getAlias',
                'getId',
                'getIndex'
            ];

            foreach($mixins as $mixin){
                $this->viewEngine->setDelegateMixin($mixin);
            }
        }

        if (func_num_args() === 0) {
            return $this->viewEngine;
        }

        return $this->viewEngine->render($view, $data);
    }

    /**
     * @inheritDoc
     */
    abstract public function viewDirectory(): string;

    /**
     * @inheritDoc
     */
    public function xhrResponse(...$args): ResponseInterface
    {
        return new JsonResponse([
            'success' => true,
        ]);
    }
}
