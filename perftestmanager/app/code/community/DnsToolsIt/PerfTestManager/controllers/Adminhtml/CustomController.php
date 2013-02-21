<?php
class DnsToolsIt_PerfTestManager_Adminhtml_CustomController extends Mage_Adminhtml_Controller_Action{
						
		
	public function indexAction(){
			$this->loadLayout()->_title($this->__('Index Action'));;
			$this->renderLayout();
	}
		
	public function newAction()
    {
        $this->_forward('edit');
    }
 
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('perftestmanager/perftestmanager');
        if ($id) {
            $model->load((int) $id);
			if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
				
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('perftestmanager')->__('Performance Test does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('perftestmanager_data', $model);
 
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }
 
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost())
        {
            $model = Mage::getModel('perftestmanager/perftestmanager');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }
            $model->setData($data);
 
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try {
                if ($id) {
                    $model->setId($id);
                }
                $model->save();
 
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('perftestmanager')->__('Error saving Performance Test'));
                }
 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('perftestmanager')->__('Performance Test was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
 
                // The following line decides if it is a "save" or "save and continue"
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
 
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if ($model && $model->getId()) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
            }
 
            return;
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('perftestmanager')->__('No data found to save'));
        $this->_redirect('*/*/');
    }
 
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('perftestmanager/perftestmanager');
                $model->setId($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('perftestmanager')->__('The performance test has been deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find the performance test to delete.'));
        $this->_redirect('*/*/');
    }
 	
	
	public function massDeleteDatapoolAction()
	{
		$ptIds = $this->getRequest()->getParam('pt_id');      // $this->getMassactionBlock()->setFormFieldName('tax_id'); from Mage_Adminhtml_Block_Tax_Rate_Grid
		if(!is_array($ptIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('perftestmanager')->__('Please select performance test(es).'));
		} else {
			try {
				$ptModel = Mage::getModel('perftestmanager/perftestmanager');
					foreach ($ptIds as $ptId) {
						$info = $this->_getInfodatapool($ptId);
						$users = $this->_getUsersbyPtName($info->name);
						$this->_deletePtUsers($users);
					}
				Mage::getSingleton('adminhtml/session')->addSuccess(
				Mage::helper('perftestmanager')->__(
				'Total of %d record(s) were deleted.', count($ptIds)
			)
			);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
	
	
	public function massExportDatapoolAction(){
		$ptIds = $this->getRequest()->getParam('pt_id');      // $this->getMassactionBlock()->setFormFieldName('tax_id'); from Mage_Adminhtml_Block_Tax_Rate_Grid
		$content="";
		if(!is_array($ptIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('perftestmanager')->__('Please select performance test(es).'));
		} else {
			try {
				$ptModel = Mage::getModel('perftestmanager/perftestmanager');
					foreach ($ptIds as $ptId) {
						$info = $this->_getInfodatapool($ptId);
						$users = $this->_getUsersbyPtName($info->name);
						foreach ($users as $user){
							 $content .= "\"{$user->getId()}\",\"{$user->getEmail()}\",\"{$info->password}\",\"{$user->getStoreId()}\"\n";
						}
						
					}
				Mage::getSingleton('adminhtml/session')->addSuccess(
				Mage::helper('perftestmanager')->__(
				'Total of %d record(s) were deleted.', count($ptIds)
			)
			);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		 $this->_prepareDownloadResponse('export.csv', $content, 'text/csv');
	}
	
	
	public function massGenerateDatapoolAction(){
		$ptIds = $this->getRequest()->getParam('pt_id');      // $this->getMassactionBlock()->setFormFieldName('tax_id'); from Mage_Adminhtml_Block_Tax_Rate_Grid
		if(!is_array($ptIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('perftestmanager')->__('Please select performance test(es).'));
		} else {
			try {
				foreach ($ptIds as $ptId) {
					$info = $this->_getInfodatapool($ptId);
					$this->_generateDatapool($info);
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
				Mage::helper('perftestmanager')->__(
				'Total of %d record(s) were deleted.', count($ptIds)
			)
			);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	private function _getInfoDatapool($id){
		$model =  Mage::getModel('perftestmanager/perftestmanager');
		$info = $model->load($id);
		return $info;
	}
	
	
	private function _getUsersbyPtName($name){
		$users =  Mage::getModel('customer/customer')->getCollection()
													 ->addAttributeToFilter('email',array('like'=>$name.'%'))
													 ->load();
		return $users;
	}
	
	private function _generateDatapool($info){
		
		 $testname=$info->name;
		 $limituser=$info->usernumber;
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
	
	private function _exportPtUsers($users){
		
	}
	
								 	
}
