<?php
class DnsToolsIt_PerfTestManager_Model_Datapool_Users extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('perftestmanager/datapool_users');
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
		 
		 /* 
		 $k=0;
		 $itofind	 = Mage::getModel('customer/customer')->getCollection();
		 
		 $itofind->getSelect()
		 		 ->reset(Zend_Db_Select::COLUMNS)
		 		 ->columns('MAX(entity_id) as entity_id');

		 $itmp = $itofind->load()->getData();
		 $k    =  $itmp[0]['entity_id'];
		 */
	     

		 $limituser+= $k;



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


	public function _deletePt($users){
		
		$model = Mage::getModel('customer/customer');
		foreach ($users as $user){
			$model->setId($user->entity_id)->delete();
		}
		
	}


	public function _getInfoDatapool($id){
		$model =  Mage::getModel('perftestmanager/perftestmanager');
		$info = $model->load($id);
		return $info;
	}

	

	public function _getPtElements($name){
		
		$users =  Mage::getModel('customer/customer')->getCollection()
													 ->addAttributeToFilter('email',array('like'=>$name.'%'))
													 ->load();
												 
		return $users;
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
