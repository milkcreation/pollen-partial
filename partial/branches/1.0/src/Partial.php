<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Closure;
use Exception;
use InvalidArgumentException;
use RuntimeException;
use League\Route\Http\Exception\NotFoundException;
use Psr\Container\ContainerInterface as Container;
use Pollen\Partial\Drivers\AccordionDriver;
use Pollen\Partial\Drivers\BreadcrumbDriver;
use Pollen\Partial\Drivers\BurgerButtonDriver;
use Pollen\Partial\Drivers\CookieNoticeDriver;
use Pollen\Partial\Drivers\CurtainMenuDriver;
use Pollen\Partial\Drivers\DropdownDriver;
use Pollen\Partial\Drivers\DownloaderDriver;
use Pollen\Partial\Drivers\FlashNoticeDriver;
use Pollen\Partial\Drivers\HolderDriver;
use Pollen\Partial\Drivers\ImageLightboxDriver;
use Pollen\Partial\Drivers\MenuDriver;
use Pollen\Partial\Drivers\ModalDriver;
use Pollen\Partial\Drivers\NoticeDriver;
use Pollen\Partial\Drivers\PaginationDriver;
use Pollen\Partial\Drivers\PdfViewerDriver;
use Pollen\Partial\Drivers\ProgressDriver;
use Pollen\Partial\Drivers\SidebarDriver;
use Pollen\Partial\Drivers\SliderDriver;
use Pollen\Partial\Drivers\SpinnerDriver;
use Pollen\Partial\Drivers\TabDriver;
use Pollen\Partial\Drivers\TableDriver;
use Pollen\Partial\Drivers\TagDriver;
use Pollen\Routing\RouteInterface;
use Pollen\Support\Concerns\BootableTrait;
use Pollen\Support\Concerns\ConfigBagTrait;
use Pollen\Support\Concerns\ContainerAwareTrait;

class Partial implements PartialInterface
{
    use BootableTrait;
    use ConfigBagTrait;
    use ContainerAwareTrait;

    /**
     * Instance de la classe.
     * @var static|null
     */
    private static $instance;

    /**
     * Définition des pilotes par défaut.
     * @var array
     */
    private $defaultDrivers = [
        'accordion'      => AccordionDriver::class,
        'breadcrumb'     => BreadcrumbDriver::class,
        'burger-button'  => BurgerButtonDriver::class,
        'cookie-notice'  => CookieNoticeDriver::class,
        'curtain-menu'   => CurtainMenuDriver::class,
        'dropdown'       => DropdownDriver::class,
        'downloader'     => DownloaderDriver::class,
        'flash-notice'   => FlashNoticeDriver::class,
        'holder'         => HolderDriver::class,
        'image-lightbox' => ImageLightboxDriver::class,
        'menu'           => MenuDriver::class,
        'modal'          => ModalDriver::class,
        'notice'         => NoticeDriver::class,
        'pagination'     => PaginationDriver::class,
        'pdf-viewer'     => PdfViewerDriver::class,
        'progress'       => ProgressDriver::class,
        'sidebar'        => SidebarDriver::class,
        'slider'         => SliderDriver::class,
        'spinner'        => SpinnerDriver::class,
        'tab'            => TabDriver::class,
        'table'          => TableDriver::class,
        'tag'            => TagDriver::class,
    ];

    /**
     * Liste des instance de pilote chargés.
     * @var PartialDriverInterface[][]
     */
    private $drivers = [];

    /**
     * Route de traitement des requêtes XHR.
     * @var RouteInterface|null
     */
    private $xhrRoute;

    /**
     * Liste des pilotes déclarés.
     * @var PartialDriverInterface[][]|Closure[][]|string[][]|array
     */
    protected $driverDefinitions = [];

    /**
     * @param array $config
     * @param Container|null $container
     */
    public function __construct(array $config = [], Container $container = null)
    {
        $this->setConfig($config);

        if (!is_null($container)) {
            $this->setContainer($container);
        }
        if (!self::$instance instanceof static) {
            self::$instance = $this;
        }
    }

    /**
     * @inheritDoc
     */
    public static function instance(): PartialInterface
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new RuntimeException(sprintf('Unavailable %s instance', __CLASS__));
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->drivers;
    }

    /**
     * @inheritDoc
     */
    public function boot(): PartialInterface
    {
        if (!$this->isBooted()) {
            //events()->trigger('partial.booting', [$this]);

            $this->xhrRoute = Router::xhr(
                md5('partial') . '/{partial}/{controller}',
                [$this, 'xhrResponseDispatcher']
            );

            $this->registerDefaultDrivers();

            $this->setBooted();
            //events()->trigger('partial.booted', [$this]);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $alias, $idOrParams = null, ?array $params = []): ?PartialDriverInterface
    {
        if (is_array($idOrParams)) {
            $params = $idOrParams;
            $id = null;
        } else {
            $id = $idOrParams;
        }

        if ($id && isset($this->drivers[$alias][$id])) {
            return $this->drivers[$alias][$id];
        }

        if (!$driver = $this->getDriverFromDefinition($alias)) {
            return null;
        }

        $this->drivers[$alias] = $this->drivers[$alias] ?? [];
        $index = count($this->drivers[$alias]);
        $id = $id ?? $alias . $index;
        if (!$driver->getAlias()) {
            $driver->setAlias($alias);
        }
        $params = array_merge($driver->defaultParams(), $this->config("driver.{$alias}", []), $params);

        $driver->setIndex($index)->setId($id)->setParams($params)->boot();

        return $this->drivers[$alias][$id] = $driver;
    }

    /**
     * Récupération d'une instance de pilote depuis une définition.
     *
     * @param string $alias
     *
     * @return PartialDriverInterface|null
     */
    protected function getDriverFromDefinition(string $alias): ?PartialDriverInterface
    {
        if (!$def = $this->driverDefinitions[$alias] ?? null) {
            throw new InvalidArgumentException(sprintf('Partial with alias [%s] unavailable', $alias));
        }

        if ($def instanceof PartialDriverInterface) {
            return clone $def;
        }
        if (is_string($def) && $this->containerHas($def)) {
            return $this->containerGet($def);
        }
        if (is_string($def) && class_exists($def)) {
            return new $def($this);
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getXhrRouteUrl(string $partial, ?string $controller = null, array $params = []): string
    {
        $controller = $controller ?? 'xhrResponse';

        return $this->xhrRoute->getUrl(array_merge($params, compact('partial', 'controller')));
    }

    /**
     * @inheritDoc
     */
    public function register(string $alias, $driverDefinition, ?Closure $callback = null): PartialInterface
    {
        if (isset($this->driverDefinitions[$alias])) {
            throw new RuntimeException(sprintf('Another PartialDriver with alias [%s] already registered', $alias));
        }
        $this->driverDefinitions[$alias] = $driverDefinition;

        if ($callback !== null) {
            $callback($this);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function registerDefaultDrivers(): PartialInterface
    {
        foreach ($this->defaultDrivers as $alias => $driverDefinition) {
            $this->register($alias, $driverDefinition);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function xhrResponseDispatcher(string $partial, string $controller, ...$args): array
    {
        try {
            $driver = $this->get($partial);
        } catch (Exception $e) {
            throw new NotFoundException(
                sprintf('PartialDriver [%s] return exception : %s', $partial, $e->getMessage())
            );
        }

        if ($driver !== null) {
            try {
                return $driver->{$controller}(...$args);
            } catch (Exception $e) {
                throw new NotFoundException(
                    sprintf('PartialDriver [%s] Controller [%s] call return exception', $controller, $partial)
                );
            }
        }
        return ['success' => false];
    }
}