<?php

class Ct_SetBackground_Adminhtml_BackgrounditemController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('setbackground/backgrounditems')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Background Item Manager'), Mage::helper('adminhtml')->__('Background Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }
    
    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('setbackground/backgrounditem')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('setbackgrounditem_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('setbackground/backgrounditems');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Background Item Manager'), Mage::helper('adminhtml')->__('Background Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Background Item News'), Mage::helper('adminhtml')->__('Background Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('setbackground/adminhtml_backgrounditem_edit'))
                    ->_addLeft($this->getLayout()->createBlock('setbackground/adminhtml_backgrounditem_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('setbackground')->__('Background Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(true);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . 'setbackground' . DS;
                    $result = $uploader->save($path, $_FILES['image']['name']);

                    //this way the name is saved in DB
                    $data['image'] = 'setbackground/' . $result['file'];
                } catch (Exception $e) {
                    
                }
            } else {
                if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                    $data['image'] = '';
                } else {
                    unset($data['image']);
                }
            }
            
            Mage::log($data);
            if (isset($data['type'])) {
                $data['type'] = intval($data['type']);
            }

            if (isset($_FILES['thumb_image']['name']) && $_FILES['thumb_image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('thumb_image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(true);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . 'setbackground' . DS;
                    $result = $uploader->save($path, $_FILES['thumb_image']['name']);

                    //this way the name is saved in DB
                    $data['thumb_image'] = 'setbackground/' . $result['file'];
                } catch (Exception $e) {
                    
                }
            } else {
                if (isset($data['thumb_image']['delete']) && $data['thumb_image']['delete'] == 1) {
                    $data['thumb_image'] = '';
                } else {
                    unset($data['thumb_image']);
                }
            }


            //$model = Mage::getModel('setbackground/backgrounditem');		
            //$model->setData($data)
            //	->setId($this->getRequest()->getParam('id'));
            $model = Mage::getModel('setbackground/backgrounditem');
            $model->setData($data);
            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }
            //exit;
            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('setbackground')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('setbackground')->__('Unable to find background item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('setbackground/backgrounditem');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $setbackgroundIds = $this->getRequest()->getParam('setbackgrounditem');
        if (!is_array($setbackgroundIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select background item(s)'));
        } else {
            try {
                foreach ($setbackgroundIds as $setbackgroundId) {
                    $setbackground = Mage::getModel('setbackground/backgrounditem')->load($setbackgroundId);
                    $setbackground->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($setbackgroundIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $setbackgroundIds = $this->getRequest()->getParam('setbackgrounditem');
        if (!is_array($setbackgroundIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select background item(s)'));
        } else {
            try {
                foreach ($setbackgroundIds as $setbackgroundId) {
                    $setbackground = Mage::getSingleton('setbackground/backgrounditem')
                            ->load($setbackgroundId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($setbackgroundIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function setOrderAction() {
        $params = $this->getRequest()->getParam('items');
        //var_dump($params);exit;
        if (!$params) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                $params = explode('|', $params);
                foreach ($params as $param) {
                    $param = explode('-', $param);
                    if (sizeof($param) > 1) {
                        $model = Mage::getModel('setbackground/backgrounditem');
                        $model->setData(array('background_order' => $param[1]))->setId($param[0]);
                        $model->save();
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($params)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'setbackground.csv';
        $content = $this->getLayout()->createBlock('setbackground/adminhtml_backgrounditem_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'setbackground.xml';
        $content = $this->getLayout()->createBlock('setbackground/adminhtml_backgrounditem_grid')
                ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}