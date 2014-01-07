<?php

class Ct_SetBackground_Block_Adminhtml_Backgrounditem_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('setbackgrounditem_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('setbackground')->__('Background Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('setbackground')->__('Item Information'),
          'title'     => Mage::helper('setbackground')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('setbackground/adminhtml_backgrounditem_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}