<?php
class DnsToolsIt_PerfTestManager_Model_Datapool_Store extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('perftestmanager/datapool_store');
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
		 Mage::log(print_r($categories,1),null,'testcatinstore.log');
		 $k=0;
		 $itofind	 = Mage::getModel('core/website')->getCollection();
		 
		 $itofind->getSelect()
		 		 ->reset(Zend_Db_Select::COLUMNS)
		 		 ->columns('MAX(website_id) as website_id');

		 $itmp = $itofind->load()->getData();
		 $k    =  $itmp[0]['website_id'];
		 
	     

		 $limituser+= $k;
		 for($i=$k;$i<$limituser;$i++){
		 	 /** @var $website Mage_Core_Model_Website */
		      $website = Mage::getModel('core/website');
		      $website->setCode($storecode.$i)
		        ->setName("www.".$storecode.$i)
		        ->save();

		      /** @var $storeGroup Mage_Core_Model_Store_Group */
		      $storeGroup = Mage::getModel('core/store_group');
		      $storeGroup->setWebsiteId($website->getId())
		        ->setName($storecode.$i)
		        ->setRootCategoryId($categories[0])
		        ->save();

		      //#addStore
			    /** @var $store Mage_Core_Model_Store */
			  $storeviews= array($storecode.$i.'_it',$storecode.$i.'_en',$storecode.$i.'_de',$storecode.$i.'_fr');
			  foreach ($storeviews as $storeview)
			  {
				  $store = Mage::getModel('core/store');
				  $store->setCode($storeview)
				        ->setWebsiteId($storeGroup->getWebsiteId())
				        ->setGroupId($storeGroup->getId())
				        ->setName($storeview)
				        ->setIsActive(1)
				        ->save();
			  }
		 }	

		   //rebuild index
	        
	        for ($i = 1; $i <= 9; $i++) {
			    $process = Mage::getModel('index/process')->load($i);
			    $process->reindexAll();
			}
			
	}


	public function _deletePt($storez){
		
		$model = Mage::getModel('core/website');
		foreach ($storez as $store){
			$model->setId($store->website_id)->delete();
		}
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
		
		$stores =  Mage::getModel('core/website')->getCollection()
													 ->addFieldToFilter('code',array('like'=>$name.'%'))
													 ->load();
												 
		return $stores;
	}

}
