<?php

class Ct_SetBackground_Model_Mysql4_Background extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the setbackground_id refers to the key field in your database table.
        $this->_init('setbackground/background', 'background_id');
    }
}