<?php
/**
 * @var Pollen\Partial\PartialTemplateInterface $this
 */
?>
<?php $this->before(); ?>
<?php echo $this->partial('tag', $this->get('trigger', [])); ?>
<?php $this->after();