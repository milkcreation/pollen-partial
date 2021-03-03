<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 * @var Pollen\Partial\Drivers\CurtainMenu\CurtainMenuItemInterface $item
 */
echo $this->partial('tag', $item->getNav());