<?php
/**
 * @var Pollen\Partial\PartialTemplateInterface $this
 * @var Pollen\Partial\Drivers\CurtainMenu\CurtainMenuItemInterface $parent
 */
echo $this->partial('tag', $parent->getTitle());