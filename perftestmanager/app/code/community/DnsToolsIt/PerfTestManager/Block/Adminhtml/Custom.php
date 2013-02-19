<?php
class DnsToolsIt_PerfTestManager_Block_Adminhtml_Custom extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_addButtonLabel = 'Add New Performance Test';
 
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_custom';
        $this->_blockGroup = 'perftestmanager';
        $this->_headerText = Mage::helper('perftestmanager')->__('Performance Test');
    }
	
	
}