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

class Ct_SetBackground_Block_Adminhtml_Backgrounditem_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('setbackgrounditem_form', array('legend' => Mage::helper('setbackground')->__('Background Item information')));

        $fielsStore = $fieldset->addField('store', 'select', array(
            'label' => Mage::helper('setbackground')->__('Store'),
            'class' => 'required-entry',
            'onchange' => "ajaxUpdate(this.value)",
            'required' => true,
            'name' => 'store',
            'values' => $this->getStoreArray()
        ));
        $fielsStore->setAfterElementHtml("
        <script type='text/javascript'>
            function storeSelect(selectValue)
            {
                console.log(selectValue);
                console.log(this.id);

                var myVariable = '<?php echo json_encode($this->getCmsPageAndCategoryArray()); ?>';
                console.log(myVariable);    
                
            }
        </script>");

        $eventType = $fieldset->addField('type', 'select', array(
            'label' => Mage::helper('setbackground')->__('Type'),
            'class' => 'required-entry',
            'required' => true,
            'onchange' => "typeSelect(this)",
            'name' => 'type',
            'values' => array(
                'category' => 'Category page',
                'page' => 'Cms page',
                'route' => 'Route page'
            ),
                ));

        /*
         * Add Ajax to the Title select box html output
         */
        $eventType->setAfterElementHtml("<script type=\"text/javascript\">
            function typeSelect(selectElement){
                var reloadurl = '" . $this
                        ->getUrl('setbackground/ajax/title') . "type/' + selectElement.value;
                new Ajax.Request(reloadurl, {
                    method: 'get',
                    onLoading: function (stateform) {
                        $('item_id').update('Searching...');
                    },
                    onComplete: function(stateform) {
                        $('item_id').update(stateform.responseText);
                    }
                });
            }
        </script>");

        $fieldset->addField('item_id', 'select', array(
            'label' => Mage::helper('setbackground')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'item_id',
            'values' => $this->getCategoryPageArray($_storeId = 1),
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('setbackground')->__('Background Image'),
            'required' => false,
            'name' => 'image',
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('setbackground')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('setbackground')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('setbackground')->__('Disabled'),
                ),
            ),
        ));

        if (Mage::getSingleton('adminhtml/session')->getSetBackgroundItemData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getSetBackgroundItemData());
            Mage::getSingleton('adminhtml/session')->setSetBackgroundItemData(null);
        } elseif (Mage::registry('setbackgrounditem_data')) {
            $form->setValues(Mage::registry('setbackgrounditem_data')->getData());
        }
        return parent::_prepareForm();
    }

    private function getStoreArray() {
        $allStores = Mage::app()->getStores();
        $store = array();
        foreach ($allStores as $_eachStoreId => $val) {
            $_storeCode = Mage::app()->getStore($_eachStoreId)->getCode();
            $_storeName = Mage::app()->getStore($_eachStoreId)->getName();
            $_storeId = Mage::app()->getStore($_eachStoreId)->getId();

            $store[] = array(
                'value' => $_storeId,
                'label' => $_storeName,
            );
        }
        return $store;
    }

    private function getCategoryPageArray($_storeId = 1) {
        $_storeId = $this->getRequest()->getParam('storeId', 1);
        $background_item_id = $this->getRequest()->getParam('id', null);
        $type = $this->getCurrentType($background_item_id, $storeId = 1);
        $collection = null;
        $cat = array();

        switch ($type) {
            case 'category':
                $rootId = Mage::app()->getStore($_storeId)->getRootCategoryId();
                $collection = Mage::getModel('catalog/category')
                        ->getCollection()
                        ->addAttributeToSelect('*')
                        ->addIsActiveFilter()
                        ->addLevelFilter(2)
                        ->addFieldToFilter('path', array('like' => "1/$rootId/%"));
                foreach ($collection as $item) {
                    $cat[] = array(
                        'value' => $item->getId(),
                        'label' => Mage::helper('setbackground')->__($item->getName())
                    );
                }
                break;
            case 'page':
                $collection = Mage::getModel('cms/page')->getCollection()->addStoreFilter($_storeId);
                foreach ($collection as $item) {
                    $cat[] = array(
                        'value' => $item->getPage_id(),
                        'label' => $item->getTitle()
                    );
                }
                break;
            case 'route':
                $cat[] = array(
                    'value' => 'contacts',
                    'label' => Mage::helper('setbackground')->__('Contact')
                );
                $cat[] = array(
                    'value' => 'customer',
                    'label' => Mage::helper('setbackground')->__('Customer')
                );
                $cat[] = array(
                    'value' => 'checkout',
                    'label' => Mage::helper('setbackground')->__('Checkout')
                );
                break;
            default:
                $rootId = Mage::app()->getStore($_storeId)->getRootCategoryId();
                $collection = Mage::getModel('catalog/category')
                        ->getCollection()
                        ->addAttributeToSelect('*')
                        ->addIsActiveFilter()
                        ->addLevelFilter(2)
                        ->addFieldToFilter('path', array('like' => "1/$rootId/%"));
                foreach ($collection as $item) {
                    $cat[] = array(
                        'value' => $item->getId(),
                        'label' => Mage::helper('setbackground')->__($item->getName())
                    );
                }
                break;
        }
        return $cat;
    }

    private function getStoreId($itemId) {
        $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()->addFieldToFilter('background_item_id', $itemId);
        foreach ($collection as $item) {
            if ($item->getStore()) {
                return $item->getStore();
            }
        }
        return false;
    }

    public function getCurrentType($background_item_id, $storeId = 1) {

        $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()
                ->addFieldToFilter('store', $storeId)
                ->addFieldToFilter('background_item_id', array('eq' => $background_item_id));
        if (is_object($collection))
            return $collection->getFirstItem()->getType();
        else
            false;
    }
}
?>

<script type='text/javascript'>
    
    function ajaxUpdate(storeId)
    {         
        jQuery.ajax({
            url: "/setbackground/ajax/list",
            type: "POST",
            data: { storeId : storeId },
            dataType: "json",
            success: function(data) 
            {                      
                jQuery("#item_id").empty();
                var mySelect = jQuery('#item_id');
                jQuery.each(data, function(val, text) { 
                    mySelect.append(
                        jQuery('<option></option>').val(text.value).html(text.value)
                    );
                });
            }
        });
    };
</script>