<?php
 
class DnsToolsIt_PerfTestManager_Block_Adminhtml_Datapool_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('datapool_grid');
        $this->setDefaultSort('pt_id');
        $this->setDefaultDir('desc');
        //$this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
       /* if (Mage::registry('preparedFilter')) {
            $this->setDefaultFilter( Mage::registry('preparedFilter') );
        }*/
    }
 





     /**
     * 
     *
     */
   /* protected function _addColumnFilterToCollection($column)
    {
        $filterArr = Mage::registry('preparedFilter');

        if ( $column->getId() === 'type' && $column->getFilter()->getValue() && strpos($column->getFilter()->getValue(), ',')) {
            $_inNin = explode(',', $column->getFilter()->getValue());
            $inNin = array();
            foreach ($_inNin as $k => $v) {
                if (is_string($v) && strlen(trim($v))) {
                    $inNin[] = trim($v);
                }
            }
            if (count($inNin)>1 && in_array($inNin[0], array('in', 'nin'))) {
                $in = $inNin[0];
                $values = array_slice($inNin, 1);
                $this->getCollection()->addFieldToFilter($column->getId(), array($in => $values));
            } else {
                parent::_addColumnFilterToCollection($column);
            }
        } elseif (is_array($filterArr) && array_key_exists($column->getId(), $filterArr) && isset($filterArr[$column->getId()])) {
            $this->getCollection()->addFieldToFilter($column->getId(), $filterArr[$column->getId()]);
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        //Zend_Debug::dump((string)$this->getCollection()->getSelect(), 'Prepared filter:');
        return $this;
    }*/


    protected function _getCollectionClass()
    {
        return 'perftestmanager/perftestmanager';
    }



    protected function _prepareCollection()
    {

        $controllername = Mage::app()->getRequest()->getControllerName();
        
        if ($controllername=='datapool'){
                    $type='users';
        }else{
                    $type=str_replace('datapool','',$controllername);
        }

        $collection = Mage::getModel('perftestmanager/perftestmanager')->getCollection()
                                                                        ->addFieldToSelect("*")
                                                                        ->addFieldToFilter('type',$type);
        

        //$collection = Mage::getModel('perftestmanager/perftestmanager')->getCollection();
        $this->setCollection($collection);
        
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {

        


       /* $coll = Mage::getModel('perftestmanager/perftestmanager')->getCollection()
                                                                 ->addFieldToSelect("type","id")
                                                                 ->addFieldToSelect("type","name");
        $coll->getSelect()->group('type');
        */
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
            'index'     => 'type',
            
                                                                            
        ));
		
		$this->addColumn('qty', array(
            'header'    => Mage::helper('perftestmanager')->__('Qty'),
            'align'     => 'left',
            'index'     => 'qty',
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
                            'base'=>'/datapool/statsview',
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

    
    //public function getGridUrl()
    //{
    //    return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/customergrid', array('_current'=>true));
    //}

    
}