<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 * @var Pollen\Partial\Drivers\CurtainMenu\CurtainMenuItemInterface $parent
 */
echo $this->partial('tag', $parent->getBack());