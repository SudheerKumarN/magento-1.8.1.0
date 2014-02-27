<?php
/**
* NOTICE OF LICENSE
*
* You may not sell, sub-license, rent or lease
* any portion of the Software or Documentation to anyone.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade to newer
* versions in the future.
*
* @category   Ct
* @package    Ct_SetBackground
* @copyright  Copyright (c) 2014 Ct Web Solutions (http://codetiburon.com/)
* @contacts   info@codetiburon.com
* @license    http://shop.etwebsolutions.com/etws-license-free-v1/   ETWS Free License (EFL1)
*/

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
