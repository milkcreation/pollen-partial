<?php
/**
 * @var Pollen\Partial\PartialTemplateInterface $this
 * @var Pollen\Partial\Drivers\Tab\TabFactoryInterface[] $items
 * @var int $depth
 */
?>
<ul class="nav Tab-nav <?php echo 'Tab-nav--' . $this->getTabStyle($depth); ?>" data-control="tab.nav">
    <?php if ($items = $this->get('items', [])) : ?>
        <?php foreach ($items as $item) : ?>
            <li class="Tab-navItem" data-control="tab.nav.item">
                <?php $this->insert('nav-item', compact('item')); ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>