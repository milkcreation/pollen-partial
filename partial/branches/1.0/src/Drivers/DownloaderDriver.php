<?php

declare(strict_types=1);

namespace Pollen\Partial\Drivers;

use Exception;
use Symfony\Component\HttpFoundation\Response as SfResponse;
use tiFy\Contracts\Routing\Route;
use tiFy\Http\Response;
use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialDriverInterface;
use tiFy\Support\MimeTypes;
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\Crypt;
use tiFy\Support\Proxy\Router;
use tiFy\Support\Proxy\Storage;
use tiFy\Support\Proxy\Url;
use tiFy\Validation\Validator as v;

class DownloaderDriver extends PartialDriver implements DownloaderDriverInterface
{
    /**
     * Url de traitement de requête HTTP.
     * @var Route|string
     */
    protected $url = '';

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();
        $this->setUrl();
    }

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(
            parent::defaultParams(),
            [
                /**
                 * @var string $basedir Chemin absolu de resolution de récupération du fichier source.
                 */
                'basedir' => ROOT_PATH,
                /**
                 * @var string $content Contenu d'affichage HTML du déclencheur de téléchargement.
                 */
                'content' => __('Télécharger', 'tify'),
                /**
                 * @var string $src Fichier à télécharger. Chemin absolu|relatif au basedir|Url du site.
                 */
                'src'     => $this->partial()->resources('/sample/sample.txt'),
                /**
                 * @var string $tag Balise HTML d'encapsulation du déclencheur de téléchargement
                 */
                'tag'     => 'a',
                /**
                 * @var string|array|null Liste des extensions|mime-type|type de fichiers permis.
                 * ex ['ppt', 'video/mp4', 'image]
                 */
                'types'   => null,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getUrl(...$params): string
    {
        return $this->url instanceof Route ? (string)$this->url->getUrl($params) : $this->url;
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        $path = base64_encode(
            json_encode(
                [
                    'basedir' => $this->get('basedir'),
                    'types'   => $this->get('types'),
                    'src'     => $this->get('src'),
                ]
            )
        );

        $url = $this->getUrl(Crypt::encrypt($path));
        if ($this->get('tag', 'a') === 'a') {
            $this->set('attrs.href', $url);
        } else {
            $this->set('attrs.data-src', $url);
        }

        $this->set(
            [
                'trigger'                    => [
                    'tag'     => $this->get('tag'),
                    'attrs'   => $this->get('attrs', []),
                    'content' => $this->get('content'),
                ],
                'trigger.attrs.data-control' => 'downloader',
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function setUrl(?string $url = null): DownloaderDriverInterface
    {
        $this->url = is_null($url)
            ? Router::get(md5($this->getAlias()) . '/{path}', [$this, 'getResponse']) : $url;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function getFilename(...$args): string
    {
        if ($decrypt = Crypt::decrypt((string)$args[0])) {
            $var = (new ParamsBag())->set(json_decode(base64_decode($decrypt), true));
        } else {
            throw new Exception(
                __('ERREUR SYSTEME : Impossible de récupérer les données de téléchargement du fichier.', 'tify')
            );
        }

        $src = $var->get('src');
        if (!is_string($src)) {
            throw new Exception(
                __('Téléchargement impossible, la fichier source n\'est pas valide.', 'tify')
            );
        } elseif (v::url()->validate(dirname($src))) {
            $path = Url::rel($src);
        } else {
            $path = $src;
        }

        if (file_exists($path)) {
            $filename = $path;
        } elseif (file_exists($var->get('basedir') . $path)) {
            $filename = $var->get('basedir') . $path;
        } else {
            throw new Exception(
                __('Téléchargement impossible, le fichier n\'est pas disponible.', 'tify')
            );
        }

        $types = $var->get('types');
        if (is_string($var->get('types'))) {
            $types = array_map('trim', explode(',', $var->get('types')));
        }

        if (!MimeTypes::inAllowedType($filename, $types)) {
            throw new Exception(
                __('Téléchargement impossible, ce type de fichier n\'est pas autorisé.', 'tify')
            );
        }

        return $filename;
    }

    /**
     * @inheritDoc
     */
    public function getResponse(string $path): SfResponse
    {
        try {
            $filename = $this->getFilename($path);
        } catch (Exception $e) {
            return new Response($e->getMessage(), 500);
        }

        try {
            return Storage::local(dirname($filename))->download(basename($filename));
        } catch (Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources("/views/downloader");
    }
}