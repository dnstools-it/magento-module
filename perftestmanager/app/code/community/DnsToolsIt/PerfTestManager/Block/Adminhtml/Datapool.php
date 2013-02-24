<?php
class DnsToolsIt_PerfTestManager_Block_Adminhtml_Datapool extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_addButtonLabel = 'Add New Performance Test';
 
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_datapool';
        $this->_blockGroup = 'perftestmanager';
        $this->_headerText = Mage::helper('perftestmanager')->__('Performance Test Manager Datapool');
    }
	
	
}