<?php
class DnsToolsIt_PerfTestManager_Model_Datapool_Products extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('perftestmanager/datapool_products');
    }
	

	public function _generateDatapool($info){
			
		
		 $info->type = "Products";
		 $testname   = $info->name;
		 $limituser  = $info->qty;
		 //dynamic
		 $firstname  = $info->name."-";
		 //dynamic
		 $lastname   = $info->name."-";
		 //dynamic
		 $email      = $info->emaildomain;
		 $password   = $info->password;
		 $websiteid  = $info->website_id;
		 $storeid    = $info->store_id;
		 $groupid    = $info->group_id;
		 $weight     = 10;
		 $sku        = $info->name."-";
		 $qty        = 10;
		 $price      = 100;
		 
		 if (preg_match("/,/",$info['categories'])){
		 	$categories = preg_split("/,/",$info['categories']);
		 }else{
		 	$categories = array($info['categories']);  
		 }
		 
		 /*
		 $k=0;
		 $itofind	 = Mage::getModel('core/website')->getCollection();
		 
		 $itofind->getSelect()
		 		 ->reset(Zend_Db_Select::COLUMNS)
		 		 ->columns('MAX(website_id) as website_id');

		 $itmp = $itofind->load()->getData();
		 $k    =  $itmp[0]['website_id'];
		 
	     

		 $limituser+= $k;
		 */
		 
		 for($i=0;$i<$limituser;$i++){
		 	$attributeSetId = 4;
	
		    //$newproduct = Mage::getModel('catalog/product');
		    $newproduct = new Mage_Catalog_Model_Product();
		
		    $newproduct->setTypeId('simple');
		    $newproduct->setWeight($weight);       
		    $newproduct->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH); 
		    $newproduct->setStatus(1);
		    $newproduct->setSku($sku.$i);
			$newproduct->setTaxClassId(0);
		    $newproduct->setWebsiteIDs(array($websiteid)); 
		    $newproduct->setStoreIDs(array($storeid));
			 
		    $newproduct->setStockData(array( 
		        'is_in_stock' => 1, 
		        'qty' => $qty,
		        'manage_stock' => 1
		    )); 
		
		    $newproduct->setAttributeSetId(4);
		    $newproduct->setName($firstname.$i);
		    $newproduct->setCategoryIds($categories); // array of categories it will relate to
		
		    $newproduct->setDescription($firstname.$i);
		    $newproduct->setShortDescription($firstname.$i);
		    $newproduct->setPrice($price);
			
			//print_r($newproduct);die();
		    try {
		        /*if (is_array($errors = $newproduct->validate())) {
		            $strErrors = array();
		            foreach($errors as $code=>$error) {
		                $strErrors[] = ($error === true)? Mage::helper('catalog')->__('Attribute "%s" is invalid.', $code) : $error;
		            }
		            $this->_fault('data_invalid', implode("\n", $strErrors));
		        }*/
		
		        $newproduct->save();
		    } catch (Mage_Core_Exception $e) {
		        $this->_fault('data_invalid', $e->getMessage());
		    }
	    }	
		}


	public function _deletePt($products){
		
		$model = Mage::getModel('catalog/product');
		foreach ($products as $product){
			$model->setId($product->entity_id)->delete();
		}
		for ($i = 1; $i <= 9; $i++) {
			    $process = Mage::getModel('index/process')->load($i);
			    $process->reindexAll();
			}
		
	}

	public function _getPtElements($sku){
		
		$products =  Mage::getModel('catalog/product')->getCollection()
													 ->addAttributeToFilter('sku',array('like'=>$sku.'%'))
													 ->load();
												 
		return $products;
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
