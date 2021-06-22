<?php
/**
 * @var Pollen\Partial\PartialTemplate $this
 * @var Pollen\Partial\Drivers\CurtainMenu\CurtainMenuItemInterface $item
 */
echo $this->partial('tag', $item->getNav());