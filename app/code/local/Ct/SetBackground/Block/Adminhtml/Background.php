<?php
class Ct_SetBackground_Block_Adminhtml_Background extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_background';
    $this->_blockGroup = 'setbackground';
    $this->_headerText = Mage::helper('setbackground')->__('Background Manager');
    $this->_addButtonLabel = Mage::helper('setbackground')->__('Add Background');
    parent::__construct();
  }
}