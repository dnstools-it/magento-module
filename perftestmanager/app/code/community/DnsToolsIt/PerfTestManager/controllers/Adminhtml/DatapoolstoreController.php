 <?php
class DnsToolsIt_PerfTestManager_Adminhtml_DatapoolstoreController extends Mage_Adminhtml_Controller_Action{
						
		
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
                $model->setType('store');
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
				$datapool= Mage::getModel('perftestmanager/perftestmanager')->getInstance('store');
					
					foreach ($ptIds as $ptId) {
						$info = $datapool->_getInfoDatapool($ptId);
						$storez = $datapool->_getPtElements($info->name);
						$datapool->_deletePt($storez);
					}
				Mage::getSingleton('adminhtml/session')->addSuccess(
				Mage::helper('perftestmanager')->__(
				'Total of %d record(s) were deleted.', count($storez)
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
				$datapool= Mage::getModel('perftestmanager/perftestmanager')->getInstance('store');
					
					foreach ($ptIds as $ptId) {
						$info = $datapool->_getInfoDatapool($ptId);
						$storez = $datapool->_getPtElements($info->name);
						foreach ($storez as $store){
							 $content .= "\"{$store->getId()}\",\"{$store->getCode()}\"\n";
						}
						
					}
				Mage::getSingleton('adminhtml/session')->addSuccess(
				Mage::helper('perftestmanager')->__(
				'Total of %d record(s) were exported.', count($storez)
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
					$datapool= Mage::getModel('perftestmanager/perftestmanager')->getInstance('store');
					$info = $datapool->_getInfodatapool($ptId);
					$datapool->_generateDatapool($info);
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

	
	
								 	
}
