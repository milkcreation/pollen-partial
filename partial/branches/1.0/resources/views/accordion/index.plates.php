<?php
/**
 * @var Pollen\Partial\PartialTemplateInterface $this
 * @var Pollen\Partial\Drivers\Accordion\AccordionCollectionInterface $items
 */
?>
<?php $this->before(); ?>
    <nav <?php $this->attrs(); ?>>
        <?php if ($items->exists()) : echo $items; endif; ?>
    </nav>
<?php $this->after();