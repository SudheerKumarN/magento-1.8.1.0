<?php

class Ct_SetBackground_Block_Adminhtml_Background_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'setbackground';
        $this->_controller = 'adminhtml_background';
        
        $this->_updateButton('save', 'label', Mage::helper('setbackground')->__('Save Background'));
        $this->_updateButton('delete', 'label', Mage::helper('setbackground')->__('Delete Background'));
		
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
        if( Mage::registry('setbackground_data') && Mage::registry('setbackground_data')->getId() ) {
            return Mage::helper('setbackground')->__("Edit Background '%s'", $this->htmlEscape(Mage::registry('setbackground_data')->getTitle()));
        } else {
            return Mage::helper('setbackground')->__('Add Background');
        }
    }
}