<?php



class Ct_SetBackground_Model_Select_Attachment
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'fixed', 'label'=>Mage::helper('adminhtml')->__('fixed')),
            array('value' => 'scroll', 'label'=>Mage::helper('adminhtml')->__('scroll')),
            array('value' => 'inherit', 'label'=>Mage::helper('adminhtml')->__('inherit')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'fixed' => Mage::helper('setbackground')->__('Fixed'),
            'scroll' => Mage::helper('setbackground')->__('Scroll'),
            'inherit' => Mage::helper('setbackground')->__('Inherit'),
        );
    }

}