<?php
/**
 * @var Pollen\Partial\PartialTemplate $this
 */
?>
<?php $this->before(); ?>
<?php echo $this->partial('tag', $this->get('trigger', [])); ?>
<?php $this->after();