<?php

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