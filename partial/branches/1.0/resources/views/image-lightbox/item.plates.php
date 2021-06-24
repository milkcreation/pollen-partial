<?php
/**
 * @var Pollen\Partial\PartialTemplateInterface $this
 * @var Pollen\Partial\Drivers\ImageLightbox\ImageLightboxItemInterface $item
 */
?>
<a <?php echo $item->getAttrs(); ?>>
    <?php echo $item->getContent(); ?>
</a>