<?php

class Ct_SetBackground_AjaxController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        
    }

    public function listAction($_storeId = 1) {

        $_storeId = $this->getRequest()->getParam('storeId', 1);
        
        $rootId     = Mage::app()->getStore($_storeId)->getRootCategoryId();
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addIsActiveFilter()
            ->addLevelFilter(2)
            ->addFieldToFilter('path', array('like'=> "1/$rootId/%"));

        $cat[] = array('value' => '=== Please select category ===', 'label' => '=== Please select category ===');
        $cat[] = array('value' => 'Default Category', 'label' => 'Default Category');
        foreach ($categories as $category) {
            $cat[] = array(
                'value' => $category->getName(),
                'label' => Mage::helper('setbackground')->__($category->getName()),
                'style' => 'padding-left:10px;',
                'class' => 'myclass',
            );
        }

        $collection = Mage::getModel('cms/page')->getCollection()->addStoreFilter($_storeId);
        $cat[] = array('value' => '=== Please select CMS Page ===', 'label' => '=== Please select category ===');

        foreach ($collection as $category) {
            $data = $category->getData();
            $cat[] = array(
                'value' => $data['title'],
                'label' => $data['title'],
                'style' => 'padding-left:10px;',
            );
        }

        echo json_encode($cat);
        exit;
//        echo json_encode(array('returned_val' => $cat)); exit;
    }
    
    public function titleAction($_storeId = 1) {
        
        $type = $this->getRequest()->getParam(type, 'category');
        
        switch ($type){
            case 'category':
                echo $this->getCategoryPageArray ();
                break;
            case 'page':
                echo $this->getCmsPageArray ();
                break;
            case 'route':
                echo $this->getRoutePageArray ();
                break;
        }
        
        exit;
    }
    
    
    private function getCmsPageArray($_storeId = 1) 
    {
        $collection = Mage::getModel('cms/page')->getCollection()->addStoreFilter($_storeId);
        $item = null;
        
        foreach ($collection as $category) {
            $data = $category->getData();
            $item .= "<option value='" . $data['page_id'] . "'>" . $data['title'] . "</option>";          
        }
        return $item;
    }
    
    private function getCategoryPageArray($_storeId = 1) 
    {
        $rootId     = Mage::app()->getStore($_storeId)->getRootCategoryId();
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addIsActiveFilter()
            ->addLevelFilter(2)
            ->addFieldToFilter('path', array('like'=> "1/$rootId/%"));

        $item = null;
        
        foreach ($categories as $category) {
            $item .= "<option value='" . $category->getId() . "'>" . Mage::helper('setbackground')->__($category->getName()) . "</option>";
        }
        return $item;
    }
    
    private function getRoutePageArray() 
    {
        $item = null;
        $item .= "<option value='contacts'>" . Mage::helper('setbackground')->__('Contact') . "</option>";
        $item .= "<option value='contacts'>" . Mage::helper('setbackground')->__('Contact') . "</option>";
        $item .= "<option value='contacts'>" . Mage::helper('setbackground')->__('Contact') . "</option>";
        echo $item;
        exit;
    }

}