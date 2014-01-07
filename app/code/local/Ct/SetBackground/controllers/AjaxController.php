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

}