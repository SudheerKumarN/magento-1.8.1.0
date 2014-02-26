<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('set_background_item')};
CREATE TABLE {$this->getTable('set_background_item')} (
  `background_item_id` int(11) unsigned NOT NULL auto_increment,
  `background_id` int(11) unsigned NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  `type` varchar(255) NOT NULL default '',
  `store` int(11) unsigned NOT NULL,
  PRIMARY KEY (`background_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();
