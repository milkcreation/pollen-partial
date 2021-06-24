<?php
/**
 * @var Pollen\Partial\PartialTemplateInterface $this
 */
?>
<?php $this->before(); ?>
<<?php echo $this->get('tag'); ?> <?php $this->attrs(); ?>
<?php if ($this->get('singleton')) : ?>
/>
<?php else : ?>
><?php $this->content(); ?></<?php echo $this->get('tag'); ?>>
<?php endif; ?>
<?php $this->after();