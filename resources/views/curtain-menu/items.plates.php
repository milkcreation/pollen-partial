<?php
/**
 * @var Pollen\Partial\PartialTemplateInterface $this
 * @var Pollen\Partial\Drivers\CurtainMenu\CurtainMenuItemInterface[] $items
 * @var Pollen\Partial\Drivers\CurtainMenu\CurtainMenuItemInterface $parent
 * @var int $depth
 */
?>
<?php if ($items = $this->get('items')) : ?>
    <div class="CurtainMenu-panel"
         data-control="curtain-menu.panel"
         data-level="<?php echo $depth; ?>"
         aria-open="<?php echo !$depth ? 'true' : 'false'; ?>"
    >
        <div class="CurtainMenu-panelWrapper">
            <div class="CurtainMenu-panelContainer">
                <?php if ($parent = $this->get('parent')) : ?>
                    <?php $this->insert('parent-title', compact('parent')); ?>

                    <?php $this->insert('parent-back', compact('parent')); ?>
                <?php endif; ?>

                <ul class="CurtainMenu-items CurtainMenu-items--<?php echo $this->get('depth'); ?>"
                    data-control="curtain-menu.items">
                    <?php foreach ($items as $item) : ?>
                        <li <?php echo $item->getAttrs(); ?>>
                            <?php $this->insert('item-nav', compact('item')); ?>

                            <?php $this->insert('items', [
                                'depth'  => $item->getDepth(),
                                'items'  => $item->getChildren(),
                                'parent' => $item,
                            ]); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif;