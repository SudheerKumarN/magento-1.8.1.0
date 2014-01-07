<?php

class Ct_SetBackground_Block_Adminhtml_Background_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('setbackground_form', array('legend'=>Mage::helper('setbackground')->__('Background information')));
     
      $fieldset->addField('identifier', 'text', array(
          'label'     => Mage::helper('setbackground')->__('Identifier'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'identifier',
      ));

	  $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('setbackground')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

	  $fieldset->addField('show_title', 'select', array(
          'label'     => Mage::helper('setbackground')->__('Show Title'),
          'name'      => 'show_title',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('setbackground')->__('Yes'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('setbackground')->__('No'),
              ),
          ),
      ));
		
//      $fieldset->addField('width', 'text', array(
//          'label'     => Mage::helper('setbackground')->__('Width (px)'),
//          'class'     => 'required-entry',
//          'required'  => true,
//          'name'      => 'width',
//      ));
//
//      $fieldset->addField('height', 'text', array(
//          'label'     => Mage::helper('setbackground')->__('Height (px)'),
//          'class'     => 'required-entry',
//          'required'  => true,
//          'name'      => 'height',
//      ));
//
//      $fieldset->addField('delay', 'text', array(
//          'label'     => Mage::helper('setbackground')->__('Delay (ms)'),
//          'class'     => 'required-entry',
//          'required'  => true,
//          'name'      => 'delay',
//      ));

	/*
      $fieldset->addField('active_from', 'text', array(
          'label'     => Mage::helper('setbackground')->__('Active From'),
          'required'  => false,
          'name'      => 'active_from',
      ));

      $fieldset->addField('active_to', 'text', array(
          'label'     => Mage::helper('setbackground')->__('Active To'),
          'required'  => false,
          'name'      => 'active_to',
      ));
	 */

	  $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('setbackground')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('setbackground')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('setbackground')->__('Disabled'),
              ),
          ),
      ));
     
//      $fieldset->addField('content', 'editor', array(
//          'name'      => 'content',
//          'label'     => Mage::helper('setbackground')->__('Content'),
//          'title'     => Mage::helper('setbackground')->__('Content'),
//          'style'     => 'width:600px; height:300px;',
//          'wysiwyg'   => false,
//          'required'  => false,
//      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getSetBackgroundData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getSetBackgroundData());
          Mage::getSingleton('adminhtml/session')->setSetBackgroundData(null);
      } elseif ( Mage::registry('setbackground_data') ) {
          $form->setValues(Mage::registry('setbackground_data')->getData());
      }
      return parent::_prepareForm();
  }
}