<?php
/**
 * @var Pollen\Partial\PartialTemplateInterface $this
 */
echo $this->partial('tag', [
    'tag'     => $this->get('tag'),
    'attrs'   => $this->get('attrs'),
    'content' => $this->get('content'),
]);