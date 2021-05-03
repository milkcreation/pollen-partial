<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 * @var Pollen\Partial\Drivers\ImageLightbox\ImageLightboxItemInterface $item
 */
?>
<a <?php echo $item->getAttrs(); ?>>
    <?php echo $item->getContent(); ?>
</a>