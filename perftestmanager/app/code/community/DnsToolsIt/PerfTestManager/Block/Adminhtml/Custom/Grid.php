<?php
 
class DnsToolsIt_PerfTestManager_Block_Adminhtml_Custom_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('custom_grid');
        $this->setDefaultSort('pt_id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('perftestmanager/perftestmanager')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('pt_id', array(
            'header'    => Mage::helper('perftestmanager')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'pt_id',
        ));
 
        $this->addColumn('name', array(
            'header'    => Mage::helper('perftestmanager')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));
 
        $this->addColumn('desc', array(
            'header'    => Mage::helper('perftestmanager')->__('Description'),
            'align'     =>'left',
            'index'     => 'desc',
        ));
 
        $this->addColumn('type', array(
            'header'    => Mage::helper('perftestmanager')->__('Type'),
            'align'     => 'left',
            'index'     => 'type',
        ));
		
		$this->addColumn('usernumber', array(
            'header'    => Mage::helper('perftestmanager')->__('UserNumber'),
            'align'     => 'left',
            'index'     => 'usernumber',
        ));
		
		  
		$this->addColumn('view',
            array(
                'header'    => Mage::helper('catalog')->__('View Stats'),
                'width'     => '40px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('View Stats'),
                        'url'     => array(
                            'base'=>'/custom/statsview',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
        ));
		
 		
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('pt_id');
		$this->getMassactionBlock()->setFormFieldName('pt_id');
		
		$this->getMassactionBlock()->addItem('delete', array(
		'label'=> Mage::helper('perftestmanager')->__('Delete Datapool'),
		'url'  => $this->getUrl('*/*/massDeleteDatapool', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
		'confirm' => Mage::helper('perftestmanager')->__('Are you sure?')
		));
		$this->getMassactionBlock()->addItem('generate_datapool', array(
		'label'=> Mage::helper('perftestmanager')->__('Generate Datapool'),
		'url'  => $this->getUrl('*/*/massGenerateDatapool', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
		'confirm' => Mage::helper('perftestmanager')->__('Are you sure?')
		));
		$this->getMassactionBlock()->addItem('export_datapool', array(
		'label'=> Mage::helper('perftestmanager')->__('Export Datapool Csv'),
		'url'  => $this->getUrl('*/*/massExportDatapool', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
		'confirm' => Mage::helper('perftestmanager')->__('Are you sure?')
		));
		
		return $this;
	}
}