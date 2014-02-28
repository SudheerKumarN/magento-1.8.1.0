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

class Ct_SetBackground_Model_Image extends Mage_Core_Model_Abstract {

    protected $_width;
    protected $_height;
    protected $_keepAspectRatio = true;
    protected $_keepFrame = true;
    protected $_keepTransparency = true;
    protected $_constrainOnly = false;
    protected $_backgroundColor = array(255, 255, 255);
    protected $_baseFile;
    protected $_newFile;
    protected $_baseDir;
    protected $_processor;
    protected $_destinationSubdir;
    protected $_angle;

    /**
     * @return Ct_SetBackground_Model_Image
     */
    public function setWidth($width) {
        $this->_width = $width;
        return $this;
    }

    public function getWidth() {
        return $this->_width;
    }

    public function setHeight($height) {
        $this->_height = $height;
        return $this;
    }

    public function getHeight() {
        return $this->_height;
    }

    public function setKeepAspectRatio($keep) {
        $this->_keepAspectRatio = (bool) $keep;
        return $this;
    }

    public function setKeepFrame($keep) {
        $this->_keepFrame = (bool) $keep;
        return $this;
    }

    public function setKeepTransparency($keep) {
        $this->_keepTransparency = (bool) $keep;
        return $this;
    }

    public function setConstrainOnly($flag) {
        $this->_constrainOnly = (bool) $flag;
        return $this;
    }

    public function setBackgroundColor(array $rgbArray) {
        $this->_backgroundColor = $rgbArray;
        return $this;
    }

    public function setSize($size) {
        // determine width and height from string
        list($width, $height) = explode('x', strtolower($size), 2);
        foreach (array('width', 'height') as $wh) {
            $$wh = (int) $$wh;
            if (empty($$wh))
                $$wh = null;
        }

        $this->setWidth($width)->setHeight($height);
        return $this;
    }

    protected function _checkMemory($file = null) {
        return $this->_getMemoryLimit() > ($this->_getMemoryUsage() + $this->_getNeedMemoryForFile($file));
    }

    protected function _getMemoryLimit() {
        $memoryLimit = ini_get('memory_limit');

        if (!isSet($memoryLimit[0])) {
            $memoryLimit = "128M";
        }

        if (substr($memoryLimit, -1) == 'M') {
            return (int) $memoryLimit * 1024 * 1024;
        }
        return $memoryLimit;
    }

    protected function _getMemoryUsage() {
        if (function_exists('memory_get_usage')) {
            return memory_get_usage();
        }
        return 0;
    }

    protected function _getNeedMemoryForFile($file = null) {
        $file = is_null($file) ? $this->getBaseFile() : $file;
        if (!$file) {
            return 0;
        }

        if (!file_exists($file) || !is_file($file)) {
            return 0;
        }

        $imageInfo = getimagesize($file);

        if (!isset($imageInfo['channels'])) {
            // if there is no info about this parameter lets set it for maximum
            $imageInfo['channels'] = 4;
        }
        if (!isset($imageInfo['bits'])) {
            // if there is no info about this parameter lets set it for maximum
            $imageInfo['bits'] = 8;
        }
        return round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
    }

    private function _rgbToString($rgbArray) {
        $result = array();
        foreach ($rgbArray as $value) {
            if (null === $value) {
                $result[] = 'null';
            } else {
                $result[] = sprintf('%02s', dechex($value));
            }
        }
        return implode($result);
    }

    public function setBaseFile($file) {
        $subDir = '';

        if ($file) {
            if (0 !== strpos($file, '/', 0)) {
                $file = '/' . $file;
            }

            $pos = strripos($file, '/');
            if ($pos !== false && $pos !== 0) {
                $subDir = substr($file, 0, $pos);
                $file = substr($file, $pos);
            }
        }

        $baseDir = Mage::getBaseDir('media') . $subDir;
        $this->_baseDir = Mage::getBaseDir('media') . DS;

        if ('/no_selection' == $file) {
            $file = null;
        }
        if ($file) {
            if ((!file_exists($baseDir . $file)) || !$this->_checkMemory($baseDir . $file)) {
                $file = null;
            }
        }

        $baseFile = $baseDir . $file;

        if ((!$file) || (!file_exists($baseFile))) {
            throw new Exception(Mage::helper('catalog')->__('Image file not found'));
        }
        $this->_baseFile = $baseFile;

        // build new filename (most important params)
        $path = array(
            'setbackground',
            'cache'
        );
        if ((!empty($this->_width)) || (!empty($this->_height)))
            $path[] = "{$this->_width}x{$this->_height}";
        // add misc params as a hash
        $path[] = md5(
                implode('_', array(
                    ($this->_keepAspectRatio ? '' : 'non') . 'proportional',
                    ($this->_keepFrame ? '' : 'no') . 'frame',
                    ($this->_keepTransparency ? '' : 'no') . 'transparency',
                    ($this->_constrainOnly ? 'do' : 'not') . 'constrainonly',
                    $this->_rgbToString($this->_backgroundColor),
                    'angle' . $this->_angle
                ))
        );
        $this->_newFile = implode('/', $path) . $file; // the $file contains heading slash
        return $this;
    }

    public function getBaseFile() {
        return $this->_baseFile;
    }

    public function getBaseDir() {
        return $this->_baseDir;
    }

    public function getNewFile() {
        return $this->_newFile;
    }

    public function setImageProcessor($processor) {
        $this->_processor = $processor;
        return $this;
    }

    public function getImageProcessor() {
        if (!$this->_processor) {
            $this->_processor = new Varien_Image($this->getBaseFile());
        }
        $this->_processor->keepAspectRatio($this->_keepAspectRatio);
        $this->_processor->keepFrame($this->_keepFrame);
        $this->_processor->keepTransparency($this->_keepTransparency);
        $this->_processor->constrainOnly($this->_constrainOnly);
        $this->_processor->backgroundColor($this->_backgroundColor);
        return $this->_processor;
    }

    public function resize() {
        if (is_null($this->getWidth()) && is_null($this->getHeight())) {
            return $this;
        }
        $this->getImageProcessor()->resize($this->_width, $this->_height);
        return $this;
    }

    public function rotate($angle) {
        $angle = intval($angle);
        $this->getImageProcessor()->rotate($angle);
        return $this;
    }

    public function setAngle($angle) {
        $this->_angle = $angle;
        return $this;
    }

    public function saveFile() {
        $this->getImageProcessor()->save($this->getBaseDir() . $this->getNewFile());
        return $this;
    }

    public function getUrl() {
        $baseDir = Mage::getBaseDir('media');
        $path = str_replace($baseDir . DS, "", $this->_newFile);
        return Mage::getBaseUrl('media') . str_replace(DS, '/', $path);
    }

    public function push() {
        $this->getImageProcessor()->display();
    }

    public function setDestinationSubdir($dir) {
        $this->_destinationSubdir = $dir;
        return $this;
    }

    public function getDestinationSubdir() {
        return $this->_destinationSubdir;
    }

    public function isCached() {
        return file_exists($this->getBaseDir() . $this->_newFile);
    }

    public function clearCache() {
        $directory = Mage::getBaseDir('media') . DS . 'gallery' . DS . 'cache' . DS;
        $io = new Varien_Io_File();
        $io->rmdir($directory, true);
    }

}