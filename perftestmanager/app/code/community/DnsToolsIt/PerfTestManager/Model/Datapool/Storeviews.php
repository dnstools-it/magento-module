<?php
class DnsToolsIt_PerfTestManager_Model_Datapool_StoreViews extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('perftestmanager/datapool_storeviews');
    }
	

	public function _generateDatapool($info){
		
		 $testname=$info->name;
		 $limituser=$info->qty;
		 //dynamic
		 $firstname=$info->name."-";
		 //dynamic
		 $lastname=$info->name."-";
		 //dynamic
		 $email=$info->emaildomain;
		 $password=$info->password;
		 $websiteid=$info->website_id;
		 $storeid=$info->store_id;
		 $groupid=$info->group_id;
		 
		 if (preg_match("/,/",$info['categories'])){
		 	$categories = preg_split("/,/",$info['categories']);
		 }else{
		 	$categories = array($info['categories']);  
		 }

		 for($i=0;$i<$limituser;$i++){
		 	 try{	
	                        //customer Model
	                        $newCustomer = Mage::getModel('customer/customer');
	                        //creazione utente
	                        $newCustomer->setFirstname($firstname.$i)
	                                    ->setLastname($lastname.$i)
	                                    ->setEmail($testname."-".$i."@".$email)
	                                    ->setPassword($password)
	                                    ->setWebsiteId($websiteid)
	                                    ->setStoreId($storeid)
	                                    ->setGroupId($groupid)
	                                    ->save();
	                        //Conferma automatica utente
	                        $newCustomer->setConfirmation(null)
	                                                ->save();
	
	        }catch(Exception $e){
	                echo $e->getMessage()."\n";
	        }
		}
		
		
	}


	private function _deletePtUsers($users){
		
		$model = Mage::getModel('customer/customer');
		foreach ($users as $user){
			$model->setId($user->entity_id)->delete();
		}
		
	}
}
