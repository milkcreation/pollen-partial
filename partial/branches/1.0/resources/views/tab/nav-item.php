<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 * @var Pollen\Partial\Drivers\Tab\TabFactoryInterface $item
 */
?>
<a <?php echo $item->getNavAttrs(); ?>>
    <?php echo $item->getTitle(); ?>
</a>
