<?php
class DnsToolsIt_PerfTestManager_Block_Adminhtml_Datapoolcategories extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_addButtonLabel = 'Add New Categories Scenario';
 
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_datapoolcategories';
        $this->_blockGroup = 'perftestmanager';
		
         //Zend_Debug::dump('test');die();
        
		 $this->_addButton('products_scenario', array(
            'label'     => Mage::helper('adminhtml')->__('Add new Products Scenario'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/datapoolproducts/new') . '\')',
            'class'     => 'back',
        ),-1,3);
		
		// $this->_addButton('orders_scenario', array(
        //    'label'     => Mage::helper('adminhtml')->__('Add new Orders Scenario'),
        //    'onclick'   => 'setLocation(\'' . $this->getUrl('*/datapoolorders/new') . '\')',
        //    'class'     => 'back',
        //),-1,5);
		
        
         $this->_addButton('categories_scenario', array(
            'label'     => Mage::helper('adminhtml')->__('Add new Categories Scenario'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/datapoolcategories/new') . '\')',
            'class'     => 'back',
        ),-1,5);

        $this->_addButton('store_scenario', array(
            'label'     => Mage::helper('adminhtml')->__('Add new Store Scenario'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/datapoolstore/new') . '\')',
            'class'     => 'back',
        ),-1,7);

        
        
        //$this->_addButton('fast_grease_magento', array(
        //    'label'     => Mage::helper('adminhtml')->__('Fast Grease'),
        //    'onclick'   => 'setLocation(\'' . $this->getUrl('*/datapoolfastglob/new') . '\')',
        //    'class'     => 'back',
        //),-1,9);
        
        

        //$this->_addButton('storeviews_scenario', array(
        //    'label'     => Mage::helper('adminhtml')->__('Add new StoreViews Scenario'),
        //    'onclick'   => 'setLocation(\'' . $this->getUrl('*/datapoolstoreviews/new') . '\')',
        //    'class'     => 'back',
        //),-1,11);


		$this->_updateButton('add','onclick','setLocation(\'' . $this->getUrl('*/datapool/new') . '\')');
		
		
        $this->_headerText = Mage::helper('perftestmanager')->__('Performance Test Manager Datapool');
		
    }
	
	
}