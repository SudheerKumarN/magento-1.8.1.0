<?php

class Ct_SetBackground_Model_Background extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('setbackground/background');
    }
}