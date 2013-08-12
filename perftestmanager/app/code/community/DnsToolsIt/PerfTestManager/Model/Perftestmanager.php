<?php
class DnsToolsIt_PerfTestManager_Model_PerfTestManager extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('perftestmanager/perftestmanager');
    }
	



	public function getInstance($type)
	{	
		switch ($type){
			
			case "orders":
				return new DnsToolsIt_PerfTestManager_Model_Datapool_Orders();
			break;
			
			case "products":
				return new DnsToolsIt_PerfTestManager_Model_Datapool_Products();
			break;
			
			case "users":
				return new DnsToolsIt_PerfTestManager_Model_Datapool_Users();
			break;

			case "store":
				return new DnsToolsIt_PerfTestManager_Model_Datapool_Store();
			break;

			case "categories":
				return new DnsToolsIt_PerfTestManager_Model_Datapool_Categories();
			break;

			

		}
		
	}


	


	
}
