<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 * @var Pollen\Partial\Drivers\Accordion\AccordionCollectionInterface $items
 */
?>
<?php $this->before(); ?>
<nav <?php $this->attrs(); ?>>
    <?php if($items->exists()) echo $items; ?>
</nav>
<?php $this->after();