<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
?>
<?php $this->before(); ?>
<?php echo $this->partial('notice', [
    'attrs'   => $this->get('attrs', []),
    'content' => $this->get('content', ''),
    'dismiss' => $this->get('dismiss', '')
]); ?>
<?php $this->after();