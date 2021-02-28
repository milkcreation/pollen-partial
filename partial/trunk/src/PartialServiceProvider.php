<?php

declare(strict_types=1);

namespace Pollen\Partial;

use Pollen\Container\BaseServiceProvider;
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

class PartialServiceProvider extends BaseServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        PartialInterface::class,
        PartialViewEngineInterface::class,
        AccordionDriver::class,
        BreadcrumbDriver::class,
        BurgerButtonDriver::class,
        CookieNoticeDriver::class,
        CurtainMenuDriver::class,
        DropdownDriver::class,
        FlashNoticeDriver::class,
        HolderDriver::class,
        ImageLightboxDriver::class,
        MenuDriver::class,
        ModalDriver::class,
        NoticeDriver::class,
        PaginationDriver::class,
        PdfViewerDriver::class,
        ProgressDriver::class,
        SidebarDriver::class,
        SliderDriver::class,
        SpinnerDriver::class,
        TabDriver::class,
        TableDriver::class,
        TagDriver::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(
            PartialInterface::class,
            function () {
                return new Partial([], $this->getContainer());
            }
        );
        $this->registerDrivers();
        $this->registerViewEngine();
    }

    /**
     * Déclaration des pilotes par défaut.
     *
     * @return void
     */
    public function registerDrivers(): void
    {
        $this->getContainer()->add(
            AccordionDriver::class,
            function () {
                return new AccordionDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            BreadcrumbDriver::class,
            function () {
                return new BreadcrumbDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            BurgerButtonDriver::class,
            function () {
                return new BurgerButtonDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            CookieNoticeDriver::class,
            function () {
                return new CookieNoticeDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            CurtainMenuDriver::class,
            function () {
                return new CurtainMenuDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            DropdownDriver::class,
            function () {
                return new DropdownDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            DownloaderDriver::class,
            function () {
                return new DownloaderDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            FlashNoticeDriver::class,
            function () {
                return new FlashNoticeDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            HolderDriver::class,
            function () {
                return new HolderDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            ImageLightboxDriver::class,
            function () {
                return new ImageLightboxDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            MenuDriver::class,
            function () {
                return new MenuDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            ModalDriver::class,
            function () {
                return new ModalDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            NoticeDriver::class,
            function () {
                return new NoticeDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            PaginationDriver::class,
            function () {
                return new PaginationDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            PdfViewerDriver::class,
            function () {
                return new PdfViewerDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            ProgressDriver::class,
            function () {
                return new ProgressDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            SidebarDriver::class,
            function () {
                return new SidebarDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            SliderDriver::class,
            function () {
                return new SliderDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            SpinnerDriver::class,
            function () {
                return new SpinnerDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            TabDriver::class,
            function () {
                return new TabDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            TableDriver::class,
            function () {
                return new TableDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
        $this->getContainer()->add(
            TagDriver::class,
            function () {
                return new TagDriver($this->getContainer()->get(PartialInterface::class));
            }
        );
    }

    /**
     * Déclaration du moteur d'affichage.
     *
     * @return void
     */
    public function registerViewEngine(): void
    {
        $this->getContainer()->add(
            PartialViewEngineInterface::class,
            function () {
                return new PartialViewEngine();
            }
        );
    }
}