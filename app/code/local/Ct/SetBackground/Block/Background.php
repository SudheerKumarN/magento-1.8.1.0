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

class Ct_SetBackground_Block_Background extends Mage_Core_Block_Template {

    protected $_background = false;

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getBackground() {
        if (!$this->_background) {

            $background_id = $this->getBackgroundId();

            if ($background_id) {
                $background = Mage::getModel('setbackground/background');
            }
        }
        return $this->_background;
    }

    public function getBackgroundItems($storeId = 1) {

        $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()
                ->addFieldToFilter('store', $storeId)
                ->addFieldToFilter('status', true)
                ->setOrder('type', 'ASC');
        return $collection;
    }

    public function getBackgroundItem($currentPlace, $storeId = 1) {

        $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()
                ->addFieldToFilter('store', $storeId)
                ->addFieldToFilter('status', true)
                ->addFieldToFilter('item_id', array('eq' => $currentPlace))
                ->setOrder('type', 'ASC');
        return $collection;
    }

    public function getBackgImgByTitle($title) {
        if ($this->isVisible()) {

            $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()
                    ->addFieldToFilter('status', true)
                    ->addFieldToFilter('title', $title)
                    ->setOrder('type', 'ASC');

            $image = null;
            foreach ($collection as $_background_image) {
                $image = $_background_image->getImage();
            }

            return $image;
        }
        return false;
    }

    public function getConfigImage() {
        $config = Mage::getStoreConfig('setbackground/image');

        if (isset($config)) return $config;
        return false;
    }

    public function isEnabled() {
        $module = Mage::app()->getConfig()->getModuleConfig('Ct_SetBackground')->active;
        $config = Mage::getStoreConfig('setbackground/info/enabled');

        if ($module AND $config) return true;
        return false;
    }

}