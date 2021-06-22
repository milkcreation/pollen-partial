<?php
/**
 * @var Pollen\Partial\PartialTemplate $this
 * @var Pollen\Partial\Drivers\CurtainMenu\CurtainMenuItemInterface $parent
 */
echo $this->partial('tag', $parent->getTitle());