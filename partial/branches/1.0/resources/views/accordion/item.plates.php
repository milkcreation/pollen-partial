<?php
/**
 * @var Pollen\Partial\PartialTemplate $this
 * @var Pollen\Partial\Drivers\Accordion\AccordionItemInterface $item
 */
?>
<div <?php echo $this->htmlAttrs($item->get('attrs', [])); ?>>
    <?php echo str_repeat('<span class="Accordion-itemPad"></span>', $item->getDepth()); ?>
    <div class="Accordion-itemContentInner">
        <?php echo $item; ?>
    </div>
</div>