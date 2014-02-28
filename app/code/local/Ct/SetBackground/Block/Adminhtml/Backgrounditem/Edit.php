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

class Ct_SetBackground_Block_Adminhtml_Backgrounditem_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'setbackground';
        $this->_controller = 'adminhtml_backgrounditem';
        
        $this->_updateButton('save', 'label', Mage::helper('setbackground')->__('Save Background Item'));
        $this->_updateButton('delete', 'label', Mage::helper('setbackground')->__('Delete Background Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('setbackground_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'setbackground_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'setbackground_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('setbackgrounditem_data') && Mage::registry('setbackgrounditem_data')->getId() ) {
            return Mage::helper('setbackground')->__("Edit Background Item '%s'", $this->htmlEscape(Mage::registry('setbackgrounditem_data')->getTitle()));
        } else {
            return Mage::helper('setbackground')->__('Add Background Item');
        }
    }
}