<?php

class Ct_SetBackground_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getParentId($category, $level = 2, $it = 10)
    {

        if ($category) {
            while($category->getLevel() != $level && $it > 0) {
                $category = $category->getParentCategory();

                if (!$category) {
                    break;
                }
            }

            if ($category) {
                return $category->getId();
            }
            else {
                return 'Cannot find parent category';
            }
        }
    }
    
    public function getParentName($category, $level = 2, $it = 10)
    {

        if ($category) {
            while($category->getLevel() != $level && $it > 0) {
                $category = $category->getParentCategory();

                if (!$category) {
                    break;
                }
            }

            if ($category) {
                return $category->getName();
            }
            else {
                return 'Cannot find parent category';
            }
        }
    }
    
    public function getStoreName()
    {
        $allStores = Mage::app()->getStores();
        $store = array();
        foreach ($allStores as $_eachStoreId => $val) {
            $_storeCode = Mage::app()->getStore($_eachStoreId)->getCode();
            $_storeName = Mage::app()->getStore($_eachStoreId)->getName();
            $_storeId = Mage::app()->getStore($_eachStoreId)->getId();

            $store[$_storeId] = $_storeName;           
        }
        return $store;
    }
}