<?php

class Ct_SetBackground_Block_Adminhtml_Backgrounditem_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    
    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('setbackgrounditem_form', array('legend' => Mage::helper('setbackground')->__('Background Item information')));

        $backgrounds = array('' => '-- Select Background --');
//        $collection = Mage::getModel('setbackground/background')->getCollection();
//        foreach ($collection as $background) {
//            $backgrounds[$background->getId()] = $background->getTitle();
//        }

//        $fieldset->addField('background_id', 'select', array(
//            'label' => Mage::helper('setbackground')->__('Background'),
//            'name' => 'background_id',
//            'required' => true,
//            'values' => $backgrounds,
//        ));

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
                var reloadurl = '". $this
                 ->getUrl('setbackground/ajax/title') . "type/' + selectElement.value;
                new Ajax.Request(reloadurl, {
                    method: 'get',
                    onLoading: function (stateform) {
                        $('title').update('Searching...');
                    },
                    onComplete: function(stateform) {
                        $('title').update(stateform.responseText);
                    }
                });
            }
        </script>");
        

        
        $eventTitle = $fieldset->addField('title', 'select', array(
            'label' => Mage::helper('setbackground')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
            'values' => $this->getCmsPageAndCategoryArray($this->getStoreId($this->getRequest()->getParam('id'))),
//            'value' => 4
                
        ));
//        Mage::log($eventTitle);


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

    /**
     * Return array params for select item
     * @param type $_storeId
     * @return array
     */
    private function getCmsPageAndCategoryArray($_storeId = 1) 
    {
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
                'value' => 'cat_' . $category->getId(),
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
                'value' => 'cms_' . $data['page_id'],
                'label' => $data['title'],
                'style' => 'padding-left:10px;',
            );
        }

        return $cat;
    }
    
    
    
    private function getStoreId($itemId) {
        $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()->addFieldToFilter('background_item_id', $itemId);
        foreach ($collection as $item){
            if($item->getStore()){
                return $item->getStore();
            }
        }
        return false;
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
    //                        var data = {
    //                            val1 : 'text1',
    //                            val2 : 'text2'
    //                        };

                jQuery("#title").empty();
                var mySelect = jQuery('#title');
                jQuery.each(data, function(val, text) { 
    //                            console.log(val);
                    mySelect.append(
                        jQuery('<option></option>').val(text.value).html(text.value)
                    );
                });
             }
        });
    };
</script>