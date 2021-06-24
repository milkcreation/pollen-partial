<?php
/**
 * @var Pollen\Partial\PartialTemplateInterface $this
 * @var Pollen\Partial\Drivers\CurtainMenu\CurtainMenuItemInterface $item
 */
echo $this->partial('tag', $item->getNav());