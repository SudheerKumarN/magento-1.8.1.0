<?php
class Ct_SetBackground_Block_Background extends Mage_Core_Block_Template
{
	protected $_background = false;

	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getBackground()     
     { 
        if (!$this->_background) {

                //$background_id = $this->getData('id');
                $background_id = $this->getBackgroundId();
//                Mage::log('$background_id=' . $background_id);

                //echo $background_id;

                if ($background_id) {
                    
//                        $background = Mage::getModel('setbackground/background')->load($background_id);
                        $background = Mage::getModel('setbackground/background');
//                        //var_dump($background);	exit;
//                        if ($background->getId()==0) {
//                                $background = Mage::getModel('setbackground/background')->load($background_id, 'identifier');
//                        }
//                        $this->_background = $background;
                }
        }
        return $this->_background;       
    }

	public function isVisible() {
//            return $this->getBackground() && $this->getBackground()->getStatus();
            return true;
	}

	public function getBackgroundItems($storeId = 1) {
            
            $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()
                    ->addFieldToFilter('store', $storeId)
                    ->addFieldToFilter('status', true)
        //                            ->addFieldToFilter('background_id', $background->getId())
                    ->setOrder('background_order','ASC');
//            Mage::log($collection->getData());
            return $collection;
	}
        
        public function getBackgroundItem($currentPlace, $storeId = 1) {
            
            $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()
                    ->addFieldToFilter('store', $storeId)
                    ->addFieldToFilter('status', true)
                    ->addFieldToFilter('title', array('eq' => $currentPlace))
                    ->setOrder('background_order','ASC');
//            Mage::log($collection->getData());
            return $collection;
	}
        
        public function getBackgImgByTitle($title) {
            if ($this->isVisible()) {

                    $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()
                            ->addFieldToFilter('status', true)
                            ->addFieldToFilter('title', $title)
                            ->setOrder('background_order','ASC');
                    
                    $image = null;
                    foreach ($collection as $_background_image){
                        $image = $_background_image->getImage(); 
                    }

                    return $image;
            }
            return false;
	}
        
        public function getConfigImage()
        {
            $config = Mage::getStoreConfig('setbackground/image');
            Mage::log($config);
            
            if(isset($config)) return $config;
            return false;
        }
        
        public function isEnabled()
        {
            $module = Mage::app()->getConfig()->getModuleConfig('Ct_SetBackground')->active;
            $config = Mage::getStoreConfig('setbackground/info/enabled');
            
            if($module AND $config) return true;
            return false;
        }
          
}