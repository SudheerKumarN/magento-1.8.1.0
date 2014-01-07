<?php

class Ct_SetBackground_Block_Adminhtml_Backgrounditem_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('setbackgrounditemGrid');
        $this->setDefaultSort('background_item_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('setbackground/backgrounditem')->getCollection()->setOrder('background_id','DESC')->setOrder('background_order','ASC');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->setTemplate('setbackground/grid.phtml');
        $this->addColumn('background_item_id', array(
          'header'    => Mage::helper('setbackground')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'background_item_id',
        ));

        $backgrounds = array();
        $collection = Mage::getModel('setbackground/background')->getCollection();
        foreach ($collection as $background) {
             $backgrounds[$background->getId()] = $background->getTitle();
        }

        $this->addColumn('background_id', array(
            'header'    => Mage::helper('setbackground')->__('Background'),
            'align'     =>'left',
            'index'     => 'background_id',
                  'type'      => 'options',
            'options'   => $backgrounds,
        ));

        $this->addColumn('image',
            array(
                'header'=> Mage::helper('setbackground')->__('Image'),
                'type'  => 'image',
                'width' => 64,
                'index' => 'image',
        ));

        $this->addColumn('store', array(
            'header'    => Mage::helper('setbackground')->__('Store view'),
            'align'     =>'left',
            'index'     => 'store',
            'type'      => 'options',
            'options'   => Mage::helper('setbackground')->getStoreName()
        ));
        
        $this->addColumn('title', array(
            'header'    => Mage::helper('setbackground')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));

        $this->addColumn('background_order', array(
            'header'    => Mage::helper('setbackground')->__('Order'),
            'align'     =>'left',
                  'width' 	  => 64,
            'index'     => 'background_order',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('setbackground')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('setbackground')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('setbackground')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('setbackground')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('setbackground')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('background_item_id');
        $this->getMassactionBlock()->setFormFieldName('setbackgrounditem');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('setbackground')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('setbackground')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('setbackground/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('setbackground')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('setbackground')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}