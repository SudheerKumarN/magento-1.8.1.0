<?php

class Ct_SetBackground_Block_Adminhtml_Backgrounditem extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_backgrounditem';
        $this->_blockGroup = 'setbackground';
        $this->_headerText = Mage::helper('setbackground')->__('Background Item Manager');
        $this->_addButton('save', array(
            'label' => Mage::helper('setbackground')->__('Save Background Item Order'),
            'onclick' => 'save_order()',
            'id' => 'save_cat',
        ));
        $this->_addButtonLabel = Mage::helper('setbackground')->__('Add Background Item');
        parent::__construct();
    }

    public function getSaveOrderUrl() {
        return $this->getUrl('*/*/setOrder');
    }

}