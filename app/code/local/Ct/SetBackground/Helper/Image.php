<?php

class Ct_SetBackground_Helper_Image extends Mage_Core_Helper_Abstract {

    protected $_model;
    protected $_scheduleResize = false;
    protected $_scheduleWatermark = false;
    protected $_scheduleRotate = false;
    protected $_angle;
    protected $_imageFile;
    protected $_placeholder;

    protected function _reset() {
        $this->_model = null;
        $this->_scheduleResize = false;
        $this->_scheduleWatermark = false;
        $this->_scheduleRotate = false;
        $this->_angle = null;
        $this->_imageFile = null;
        return $this;
    }

    public function init($imageFile) {
        $this->_reset();
        $this->_setModel(Mage::getModel('setbackground/image'));
        $this->setImageFile($imageFile);
        return $this;
    }

    public function resize($width, $height = null) {
        $this->_getModel()->setWidth($width)->setHeight($height);
        $this->_scheduleResize = true;
        return $this;
    }

    public function keepAspectRatio($flag) {
        $this->_getModel()->setKeepAspectRatio($flag);
        return $this;
    }

    public function keepFrame($flag, $position = array('center', 'middle')) {
        $this->_getModel()->setKeepFrame($flag);
        return $this;
    }

    public function keepTransparency($flag, $alphaOpacity = null) {
        $this->_getModel()->setKeepTransparency($flag);
        return $this;
    }

    public function constrainOnly($flag) {
        $this->_getModel()->setConstrainOnly($flag);
        return $this;
    }

    public function backgroundColor($colorRGB) {
        // assume that 3 params were given instead of array
        if (!is_array($colorRGB)) {
            $colorRGB = func_get_args();
        }
        $this->_getModel()->setBackgroundColor($colorRGB);
        return $this;
    }

    public function rotate($angle) {
        $this->setAngle($angle);
        $this->_getModel()->setAngle($angle);
        $this->_scheduleRotate = true;
        return $this;
    }

    public function placeholder($fileName) {
        $this->_placeholder = $fileName;
    }

    public function getPlaceholder() {
        if (!$this->_placeholder) {
            $attr = $this->_getModel()->getDestinationSubdir();
            $this->_placeholder = 'images/placeholder/' . $attr . '.jpg';
        }
        Mage::log($this->_imageFile, null, 'image.log');
        return $this->_placeholder;
    }

    public function __toString() {
        try {
            if ($this->getImageFile()) {
                $this->_getModel()->setBaseFile($this->getImageFile());
            }

            if ($this->_getModel()->isCached()) {
                return $this->_getModel()->getUrl();
            } else {
                if ($this->_scheduleRotate) {
                    $this->_getModel()->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $this->_getModel()->resize();
                }

                if ($this->_scheduleWatermark) {
                    $this->_getModel()
                            ->setWatermarkPosition($this->getWatermarkPosition())
                            ->setWatermarkSize($this->parseSize($this->getWatermarkSize()))
                            ->setWatermark($this->getWatermark(), $this->getWatermarkPosition());
                } else {
                    if ($watermark = Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_image")) {
                        $this->_getModel()
                                ->setWatermarkPosition($this->getWatermarkPosition())
                                ->setWatermarkSize($this->parseSize($this->getWatermarkSize()))
                                ->setWatermark($watermark, $this->getWatermarkPosition());
                    }
                }

                $url = $this->_getModel()->saveFile()->getUrl();
            }
        } catch (Exception $e) {

            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }
        return $url;
    }

    protected function _setModel($model) {
        $this->_model = $model;
        return $this;
    }

    protected function _getModel() {
        return $this->_model;
    }

    protected function setImageFile($file) {
        $this->_imageFile = $file;
        return $this;
    }

    protected function getImageFile() {
        return $this->_imageFile;
    }

}