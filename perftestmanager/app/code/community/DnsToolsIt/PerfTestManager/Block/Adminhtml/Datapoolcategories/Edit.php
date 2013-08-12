<?php
class DnsToolsIt_PerfTestManager_Block_Adminhtml_Datapoolcategories_Edit extends 
                                            Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
       

        $this->_objectId = 'id';
        $this->_blockGroup = 'perftestmanager';
        $this->_controller = 'adminhtml_datapoolcategories';
        $this->_mode = 'edit';
 
        $this->_addButton('save_and_continue', array(
                  'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
                  'onclick' => 'saveAndContinueEdit()',
                  'class' => 'save',
        ), -100);

        
        $this->_updateButton('save', 'label', Mage::helper('perftestmanager')->__('Save Performance Test'));
 
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }
 
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
 
    public function getHeaderText()
    {
        if (Mage::registry('perftestmanager_data') && Mage::registry('perftestmanager_data')->getId())
        {
            return Mage::helper('perftestmanager')->__('Edit Performance Test "%s"', $this->htmlEscape(Mage::registry('perftestmanager_data')->getName()));
        } else {
            return Mage::helper('perftestmanager')->__('New Performance Test');
        }
    }
 
}