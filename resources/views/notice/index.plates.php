<?php
/**
 * @var Pollen\Partial\PartialTemplate $this
 */
?>
<?php $this->before(); ?>
<?php echo $this->partial('tag', [
    'tag'     => 'div',
    'attrs'   => $this->get('attrs', []),
    'content' => $this->fetch('content', $this->all()) . $this->get('dismiss', '')
]); ?>
<?php $this->after();