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
    
    public function getTypeName()
    {
        return array(
                'category'  => Mage::helper('setbackground')->__('Category page'),
                'page'      => Mage::helper('setbackground')->__('Cms page'),
                'route'     => Mage::helper('setbackground')->__('Route page'),
            );
    }
    
    public function getCurrentPlace()
    {
        $category = Mage::registry('current_category');         
        $product  = Mage::registry('current_product');
        $homepage = Mage::getBlockSingleton('page/html_header')->getIsHomePage();
        $cms = Mage::getSingleton('cms/page')->getIdentifier();
        
        if(isset($category) AND isset($product)){                               //view page
            return $category->getId() . ' - ' . $product->getId();
        }
        elseif ($category) {                                                    //category page
            return $category->getId();
        }
        elseif($homepage AND isset($cms)){                                      //home page
            return Mage::getSingleton('cms/page')->getId();
        }
        elseif(!$homepage AND isset($cms)){                                     //cms page
            return Mage::getSingleton('cms/page')->getId();
        }
        else{                                                                   //mage route
            return Mage::app()->getFrontController()->getRequest()->getRouteName();
        }
    }
}