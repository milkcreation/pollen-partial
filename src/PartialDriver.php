<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Closure;
use BadMethodCallException;
use InvalidArgumentException;
use Pollen\Http\JsonResponse;
use Pollen\Http\JsonResponseInterface;
use Pollen\Http\Request;
use Pollen\Http\RequestInterface;
use Pollen\Support\Concerns\BootableTrait;
use Pollen\Support\Concerns\HttpRequestAwareTrait;
use Pollen\Support\Concerns\ParamsBagAwareTrait;
use Pollen\Support\HtmlAttrs;
use Pollen\Support\Str;
use Throwable;

/**
 * @mixin \Pollen\Support\ParamsBag
 */
abstract class PartialDriver implements PartialDriverInterface
{
    use BootableTrait;
    use HttpRequestAwareTrait;
    use ParamsBagAwareTrait;

    /**
     * Indice de l'instance dans le gestionnaire.
     * @var int
     */
    private $index = 0;

    /**
     * Instance du gestionnaire.
     * @var PartialManagerInterface
     */
    private $partialManager;

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
     * Instance de la requête HTTP associée.
     * @var RequestInterface
     */
    protected $request;

    /**
     * Instance du moteur de gabarits d'affichage.
     * @var PartialViewEngineInterface
     */
    protected $viewEngine;

    /**
     * @param PartialManagerInterface $partialManager
     */
    public function __construct(PartialManagerInterface $partialManager)
    {
        $this->partialManager = $partialManager;
    }

    /**
     * @inheritDoc
     */
    public function __get(string $key)
    {
        return $this->params($key);
    }

    /**
     * @inheritDoc
     */
    public function __call(string $method, array $arguments)
    {
        try {
            return $this->params()->{$method}(...$arguments);
        } catch (Throwable $e) {
            throw new BadMethodCallException(
                sprintf(
                    'PartialDriver [%s] method call [%s] throws an exception: %s',
                    $this->getAlias(),
                    $method,
                    $e->getMessage()
                )
            );
        }
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
    public function getRequest(): RequestInterface
    {
        if (is_null($this->request)) {
            $this->request = $this->partialManager()->containerHas(RequestInterface::class)
                ? $this->partialManager()->containerGet(RequestInterface::class)
                : Request::createFromGlobals();
        }

        return $this->request;
    }

    /**
     * @inheritDoc
     */
    public function getXhrUrl(array $params = []): string
    {
        return $this->partialManager()->getXhrRouteUrl($this->getAlias(), null, $params);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): PartialDriverInterface
    {
        return $this->parseAttrId()->parseAttrClass();
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
    public function partialManager(): PartialManagerInterface
    {
        return $this->partialManager;
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
    public function setRequest(RequestInterface $request): PartialDriverInterface
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setViewEngine(PartialViewEngineInterface $viewEngine): PartialDriverInterface
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
            $default = $this->partialManager()->config('default.driver.viewer', []);

            if (isset($default['directory'])) {
                $default['directory'] = rtrim($default['directory'], '/') . '/' . $this->getAlias();
                if (file_exists($default['directory'])) {
                    $directory = $default['directory'];
                }
            }

            if (isset($default['override_dir'])) {
                $default['override_dir'] = rtrim($default['override_dir'], '/') . '/' . $this->getAlias();
                if (file_exists($default['override_dir'])) {
                    $overrideDir = $default['override_dir'];
                }
            }

            if ($directory === null) {
                $directory = $this->get('viewer.directory', null);
                if ($directory && !file_exists($directory)) {
                    $directory = null;
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

            if ($overrideDir === null) {
                $overrideDir = $this->get('viewer.override_dir', null);
                if ($overrideDir && !file_exists($overrideDir)) {
                    $overrideDir = null;
                }
            }

            $this->viewEngine = $this->partialManager()->containerHas(PartialViewEngineInterface::class)
                ? $this->partialManager()->containerGet(PartialViewEngineInterface::class)
                : new PartialViewEngine();

            $this->viewEngine->setDirectory($directory)->setDelegate($this);

            if ($overrideDir !== null) {
                $this->viewEngine->addFolder('_override_dir', $overrideDir, true);
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
    public function xhrResponse(...$args): JsonResponseInterface
    {
        return new JsonResponse([
            'success' => true,
        ]);
    }
}
