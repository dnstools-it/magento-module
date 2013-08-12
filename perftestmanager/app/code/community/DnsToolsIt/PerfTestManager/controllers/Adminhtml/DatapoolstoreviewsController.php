<?php
class DnsToolsIt_PerfTestManager_Adminhtml_DatapoolstoreviewsController extends Mage_Adminhtml_Controller_Action{
						
		
	public function indexAction(){
			$this->loadLayout()->_title($this->__('Index Action'));
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
		//for add tabs in a form new action
		//$this->_addContent($this->getLayout()->createBlock('form/adminhtml_form_edit'))->_addLeft($this->getLayout()->createBlock('form/adminhtml_form_edit_tabs')); 
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
			/*trasform array in flat varchar separator ","*/
			foreach ($data as $key => $value)
		    {
		        if (is_array($value))
		        {
		            $data[$key] = implode(',',$this->getRequest()->getParam($key)); 
		        }
		    }  
		
		
            $model->setData($data);
 
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try {
                if ($id) {
                    $model->setId($id);
                }
                $model->setType('storeviews');
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
						$users = $this->_getProductbyPtName($info->name);
						$this->_deletePtProducts($users);
					}
				Mage::getSingleton('adminhtml/session')->addSuccess(
				Mage::helper('perftestmanager')->__(
				'Total of %d record(s) were deleted.', count($users)
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
				'Total of %d record(s) were exported.', count($users)
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
				'Total of %d record(s) were generated.',$info->qty
			)
			);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
	
	public  function  statsviewAction()
	{
		$id = $this->getRequest()->getParam('id');  
		$infodp = $this->_getInfoDatapool($id);
		$users = $this->_getUsersbyPtName($infodp->name);
		$stats = $this->_getUsersStat($users);
		$this->loadLayout();
		$block=$this->getLayout()->createBlock("perftestmanager/viewstats")->setTemplate("perftestmanager/stats.phtml");
		$block->setData('stats',$stats);
		$block->setData('infodp',$infodp);
		$this->getLayout()->getBlock('content')->append($block);
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
	}

	private function _getInfoDatapool($id){
		$model =  Mage::getModel('perftestmanager/perftestmanager');
		$info = $model->load($id);
		return $info;
	}
	
	private function _getUsersStat($users){
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
	
	private function _getUsersbyPtName($name){
		
		$users =  Mage::getModel('customer/customer')->getCollection()
													 ->addAttributeToFilter('email',array('like'=>$name.'%'))
													 ->load();
												 
		return $users;
	}
	
	
	private function _getProductbyPtName($sku){
		
		$prodcuts =  Mage::getModel('catalog/product')->getCollection()
													 ->addAttributeToFilter('sku',array('like'=>$sku.'%'))
													 ->load();
												 
		return $prodcuts;
	}
	
	private function _generateDatapool($info){
			
		
		 $info->type = "Storeviews";
		  for($i=0;$i<$limituser;$i++){
		 //#addWebsiteStore
		    /** @var $website Mage_Core_Model_Website */
		    $website = Mage::getModel('core/website');
		    $website->setCode('<your_website_code_here>')
		        ->setName('<your_website_name>')
		        ->save();

		//#addStoreGroup
		    /** @var $storeGroup Mage_Core_Model_Store_Group */
		    $storeGroup = Mage::getModel('core/store_group');
		    $storeGroup->setWebsiteId($website->getId())
		        ->setName('<your_store_name>')
		        ->setRootCategoryId('<needed_root_category_id>')
		        ->save();

		//#addStore
		    /** @var $store Mage_Core_Model_Store */
		    $store = Mage::getModel('core/store');
		    $store->setCode('<your_store_view_code_here>')
		        ->setWebsiteId($storeGroup->getWebsiteId())
		        ->setGroupId($storeGroup->getId())
		        ->setName('<your_store_view_name>')
		        ->setIsActive(1)
		        ->save();
			
			//print_r($newproduct);die();
		    try {
		        if (is_array($errors = $newproduct->validate())) {
		            $strErrors = array();
		            foreach($errors as $code=>$error) {
		                $strErrors[] = ($error === true)? Mage::helper('catalog')->__('Attribute "%s" is invalid.', $code) : $error;
		            }
		            $this->_fault('data_invalid', implode("\n", $strErrors));
		        }
		
		        $newproduct->save();
		    } catch (Mage_Core_Exception $e) {
		        $this->_fault('data_invalid', $e->getMessage());
		    }
	    }	
		}

	private function _deletePtProducts($users){
		$model = Mage::getModel('catalog/product');
		foreach ($users as $user){
			$model->setId($user->entity_id)->delete();
		}
		
	}
	
	private function _exportPtUsers($users){
		
	}
	
								 	
}
