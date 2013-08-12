<?php
class DnsToolsIt_PerfTestManager_Model_Datapool_Fastgrease extends Mage_Core_Model_Abstract
{
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('perftestmanager/datapool_fastgrease');
    }
	

	public function _generateDatapool($info){
			
		 $info->type = "Store";
		 $testname   = $info->name;
		 $limituser  = $info->qty;
		 $storecode  = $info->name."_";

		 if (preg_match("/,/",$info['categories'])){
		 	$categories = preg_split("/,/",$info['categories']);
		 }else{
		 	$categories = array($info['categories']);  
		 }
		 
		 	//call every _generateDatapool Method to complete the schema

		   //rebuild index
	        
	        for ($i = 1; $i <= 9; $i++) {
			    $process = Mage::getModel('index/process')->load($i);
			    $process->reindexAll();
			}
			
	}


	public function _deletePt($storez){
		
		//call every _deletePt of related object
		for ($i = 1; $i <= 9; $i++) {
			    $process = Mage::getModel('index/process')->load($i);
			    $process->reindexAll();
			}
		
	}

	public function _getInfoDatapool($id){
		$model =  Mage::getModel('perftestmanager/perftestmanager');
		$info = $model->load($id);
		return $info;
	}

	

	public function _getPtElements($name){
		//how to make this method for every object related
		$stores =  Mage::getModel('core/website')->getCollection()
													 ->addFieldToFilter('code',array('like'=>$name.'%'))
													 ->load();
												 
		return $stores;
	}

}
