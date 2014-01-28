<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('set_background_item')};
CREATE TABLE {$this->getTable('set_background_item')} (
  `background_item_id` int(11) unsigned NOT NULL auto_increment,
  `background_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `image_url` varchar(512) NOT NULL default '',
  `thumb_image` varchar(255) NOT NULL default '',
  `thumb_image_url` varchar(512) NOT NULL default '',
  `content` text NULL default '',
  `link_url` varchar(512) NOT NULL default '#',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  `store` int(11) unsigned NOT NULL,
  PRIMARY KEY (`background_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 


//-- DROP TABLE IF EXISTS {$this->getTable('set_background')};
//CREATE TABLE {$this->getTable('set_background')} (
//  `background_id` int(11) unsigned NOT NULL auto_increment,
//  `identifier` varchar(255) NOT NULL default '',
//  `title` varchar(255) NOT NULL default '',
//  `show_title` smallint(6) NOT NULL default '0',
//  `content` text NULL default '',
//  `width` int(11) unsigned NULL,
//  `height` int(11) unsigned NULL,
//  `delay` int(11) unsigned NULL,
//  `status` smallint(6) NOT NULL default '0',
//  `active_from` datetime NULL,
//  `active_to` datetime NULL,
//  `created_time` datetime NULL,
//  `update_time` datetime NULL,
//  PRIMARY KEY (`background_id`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8;


//,
//  CONSTRAINT `FK_SET_BACKGROUND_ITEM` FOREIGN KEY (`background_id`) REFERENCES `{$this->getTable('set_background')}` (`background_id`) ON DELETE CASCADE ON UPDATE CASCADE