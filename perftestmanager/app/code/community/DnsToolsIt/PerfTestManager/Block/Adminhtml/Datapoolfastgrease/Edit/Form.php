<?php
class DnsToolsIt_PerfTestManager_Block_Adminhtml_Datapoolfastgrease_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        if (Mage::getSingleton('adminhtml/session')->getExampleData())
        {
            $data = Mage::getSingleton('adminhtml/session')->getExamplelData();
            Mage::getSingleton('adminhtml/session')->getExampleData(null);
        }
        elseif (Mage::registry('perftestmanager_data'))
        {
            $data = Mage::registry('perftestmanager_data')->getData();
        }
        else
        {
            $data = array();
        }
 
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
        ));
 
        $form->setUseContainer(true);
 
        $this->setForm($form);

        
        $fieldset = $form->addFieldset('datapool_form', array(
             'legend' =>Mage::helper('perftestmanager')->__('Performance Test Information')
        ));
 
        $fieldset->addField('name', 'text', array(
             'label'     => Mage::helper('perftestmanager')->__('Name'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'name',
             'note'     => Mage::helper('perftestmanager')->__('The name of the Performance Test.'),
        ));
 
        $fieldset->addField('desc', 'text', array(
             'label'     => Mage::helper('perftestmanager')->__('Description'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'desc',
             'note'     => Mage::helper('perftestmanager')->__('Short Description'),
        ));
	
		/*$fieldset->addField('type', 'hidden', array(
              'default_html'   => '<input id="type" name="type" value="users" type="hidden">'
        ));*/
		
		$fieldset->addField('qty', 'text', array(
             'label'     => Mage::helper('perftestmanager')->__('User Number'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'qty',
             'note'     => Mage::helper('perftestmanager')->__('Number of customer that populate Database.'),
        ));
		
		
		
		/*
        $fieldset->addField('website_id', 'select', array(
                'name'      => 'website_id',
                'label'     => Mage::helper('perftestmanager')->__('WebSite'),
                'title'     => Mage::helper('perftestmanager')->__('WebSite'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(true, false),
                 'note'     => Mage::helper('perftestmanager')->__('Web Site Id used for datapool'),
            )); 
		
	 	$fieldset->addField('store_id', 'select', array(
                'name'      => 'store_id',
                'label'     => Mage::helper('perftestmanager')->__('Store'),
                'title'     => Mage::helper('perftestmanager')->__('Store'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(true, false),
                'note'     => Mage::helper('perftestmanager')->__('Store Id used for datapool'),
            )); 
			
	
			
			$fieldset->addField('group_id', 'select', array(
                'name'      => 'group_id',
                'label'     => Mage::helper('perftestmanager')->__('Group'),
                'title'     => Mage::helper('perftestmanager')->__('Group'),
                'required'  => true,
                'values'    => Mage::getModel('customer/group')->getCollection()->toOptionArray(),
                'note'     => Mage::helper('perftestmanager')->__('Group Id of Customer in Datapool'),
            )); 
	
		*/
		
 
        
 
        $form->setValues($data);
 
        return parent::_prepareForm();
    }
}