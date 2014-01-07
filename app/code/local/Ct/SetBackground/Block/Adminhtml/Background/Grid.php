<?php

class Ct_SetBackground_Block_Adminhtml_Background_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('setbackgroundGrid');
      $this->setDefaultSort('background_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('setbackground/background')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('background_id', array(
          'header'    => Mage::helper('setbackground')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'background_id',
      ));

      $this->addColumn('identifier', array(
          'header'    => Mage::helper('setbackground')->__('Identifier'),
          'align'     =>'left',
          'index'     => 'identifier',
      ));

	  $this->addColumn('title', array(
          'header'    => Mage::helper('setbackground')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

      $this->addColumn('show_title', array(
          'header'    => Mage::helper('setbackground')->__('Show Title'),
          'align'     => 'left',
          'width'     => '40px',
          'index'     => 'show_title',
          'type'      => 'options',
          'options'   => array(
              1 => 'Yes',
              2 => 'No',
          ),
      ));

	  $this->addColumn('width', array(
          'header'    => Mage::helper('setbackground')->__('Width'),
          'align'     =>'right',
          'width'     => '40px',
          'index'     => 'width',
      ));

	  	  $this->addColumn('height', array(
          'header'    => Mage::helper('setbackground')->__('Height'),
          'align'     =>'right',
          'width'     => '40px',
          'index'     => 'height',
      ));

	  $this->addColumn('delay', array(
          'header'    => Mage::helper('setbackground')->__('Delay'),
          'align'     =>'right',
          'width'     => '40px',
          'index'     => 'delay',
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
        $this->setMassactionIdField('background_id');
        $this->getMassactionBlock()->setFormFieldName('setbackground');

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