<?php

class DnsToolsIt_PerfTestManager_Model_Observer{
		
	public $deltatime;
	
	public $starttime;
	
	public $endtime;
	
	public function saveLoginTime( $observer ){
		$this->endtime=microtime(true);
		$this->starttime=Mage::registry('starttime');
		$this->deltatime=$this->endtime - $this->starttime;
		$customer = $observer->getCustomer();
	
		try{
		$model=Mage::getModel("perftestmanager/statsinfo")
    				->setemail($customer->getEmail())
    				->setlogin_time($this->deltatime)
					->save();
		}catch(Exception $e){
			Zend_Debug::dump($e->getMessage());
		}
	}
	/*@todo
	public function saveLogOutTime($observer){
		$postData = Mage::app()->getRequest()->getPost();
		$customer = $observer->getCustomer();
		$model=Mage::getModel("statsinfo/statsinfo")
    				->setid_user($customer->getId())
    				->setid_pt($postData['privacy'])
					->setlogout_time($postData['gcs_0'])
					->save();
	}
	*/
	
	public function saveSessionInit(){
		Mage::register('starttime', microtime(true));
		//Zend_Debug::dump($this->starttime);die();
	}

}
