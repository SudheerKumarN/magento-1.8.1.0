<?php
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('set_background_item')} ADD `background_order` int(11) default 0;

");
$installer->endSetup();
?>