<?php
class DnsToolsIt_PerfTestManager_Model_Datapool_Categories extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('perftestmanager/datapool_products');
    }
	

	public function _generateDatapool($info){
			
		
		 $testname   = $info->name;
		 $limituser  = $info->qty;
		 //dynamic
		 $catname    = $info->name."-";
		 $websiteid  = $info->website_id;
		 $storeid    = $info->store_id;
		 $groupid    = $info->group_id;

		 /*
		 $k=0;
		 $itofind	 = Mage::getModel('catalog/category')->getCollection();
		 
		 $itofind->getSelect()
		 		 ->reset(Zend_Db_Select::COLUMNS)
		 		 ->columns('MAX(website_id) as website_id');

		 $itmp = $itofind->load()->getData();
		 $k    =  $itmp[0]['website_id'];
		 
	     

		 $limituser+= $k;
		*/
		 
		 
		 for($i=0;$i<$limituser;$i++){
		 	
			 //root category
			//Mage::app()->getWebsite(true)->getDefaultStore()->getRootCategoryId()
			$subcategories = array('alfa','beta','gamma','teta');

			$category = new Mage_Catalog_Model_Category();
			$category->setName($catname.$i);
			$category->setUrlKey($catname.$i);
			$category->setIsActive(1);
			$category->setDisplayMode('PRODUCTS');
			$category->setIsAnchor(1);
			$category->setDescription('This is a '.$catname.$i);
			$category->setAttributeSetId(3);

			$parentCategory = Mage::getModel('catalog/category')->load(1);
			$category->setPath($parentCategory->getPath()); 
			        
			try {
			    $category->save();
			    $rootCategoryId = $category->getId();
			 }
			  catch (Exception $e){
			    echo $e->getMessage();
			    Mage::log("Message: ".$e->getMessage(),null,'testcategory.log');
			} 
			
			

			/* To create a category within another category, which you know by name */
			$parentId = $rootCategoryId;
			Mage::log($rootCategoryId."-".$catname.$i,null,'testcategory.log');
			
			if ($parentId) 
			{
			 foreach ($subcategories as $subcat)
			 {
			        $urlKey = $catname.$i."-".$subcat;
			 		$category = Mage::getModel('catalog/category');
			        $category->setName($catname.$i."-".$subcat)
			        ->setUrlKey($urlKey)
			        ->setIsActive(1)
			        ->setDisplayMode('PRODUCTS_AND_PAGE')
			        ->setIsAnchor(0)
			        ->setDescription('This is a '.$catname.$i."-".$subcat)
			        ->setAttributeSetId($category->getDefaultAttributeSetId());
			 
			        $parentCategory = Mage::getModel('catalog/category')->load($parentId);
			        $category->setPath($parentCategory->getPath());              
			 		try 
			 		{
			 		 	$category->save();
				    } catch (Mage_Core_Exception $e) 
				    {
				        $this->_fault('data_invalid', $e->getMessage());
				    }
			  }
			
			}
			
			//reindex all
			
			
		   
	     }	
	     for ($i = 1; $i <= 9; $i++) 
		 {
			    $process = Mage::getModel('index/process')->load($i);
			    $process->reindexAll();
		 }
	}


	public function _deletePt($categories){
		
		$model = Mage::getModel('catalog/category');
		foreach ($categories as $category){
			$model->setId($category->entity_id)->delete();
		}
		
	}

	public function _getPtElements($name){
		
		$categories =  Mage::getModel('catalog/category')->getCollection()
													 ->addAttributeToFilter('name',array('like'=>$name.'%'))
													 ->load();
												 
		return $categories;
	}

	public function _getInfoDatapool($id){
		$model =  Mage::getModel('perftestmanager/perftestmanager');
		$info = $model->load($id);
		return $info;
	}



	public function _getStat($users){
		$stats=array();	
		
		$stats['hasloggedandAction']=0;
		$stats['haslogged']=array();
		$stats['avgspeedlogin']=0;
		$stats['hasloggedcount']=0;
		
		foreach ($users as $user){
			
			$created=new DateTime($user->created_at);
			$updated=new DateTime($user->updated_at);
			//check login and action
			if ($updated!=$created){
				$stats['hasloggedandAction']+=1;
			}
			
			//check firstlogin   
			//Mage::getModel('log/customer')
			$logtime =  Mage::getModel('log/customer')->loadByCustomer($user->entity_id);
		
			//Zend_Debug::dump($logtime->getData());
			if (array_key_exists('login_at',$logtime->getData()))
			{
				$stats['haslogged'][$user->entity_id]=1;
				
			}

			
			
		}
	
		if (count($users)>0){
			foreach ($stats['haslogged'] as $userid=>$value)
				{
					$stats['hasloggedcount']+=$value;
				}
			//check media velocita di login (observer + tabella dedicata)
			$avgspeed = Mage::getModel('statsinfo/statsinfo')->getCollection()
																 ->addFieldToSelect('*')
																 ->load();
		
			foreach ($avgspeed->getData() as $speedlogin){
					$stats['avgspeedlogin'] = $speedlogin['login_time'] /count ($avgspeed->getData());
				}
		}
		return $stats;
	}
}
