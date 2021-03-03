<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
echo $this->partial('tag', [
    'tag'     => $this->get('tag'),
    'attrs'   => $this->get('attrs'),
    'content' => $this->get('content'),
]);