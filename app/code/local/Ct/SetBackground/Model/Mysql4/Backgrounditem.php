<?php

class Ct_SetBackground_Model_Mysql4_Backgrounditem extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the easybackground_id refers to the key field in your database table.
        $this->_init('setbackground/backgrounditem', 'background_item_id');
    }
}