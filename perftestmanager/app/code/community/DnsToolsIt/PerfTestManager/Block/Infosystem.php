<?php
class DnsToolsIt_PerfTestManager_Block_Infosystem extends Mage_Adminhtml_Block_Widget_Form_Container
{
 
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_infosystem';
        $this->_blockGroup = 'perftestmanager';
         $this->_addButton('save_and_continue', array(
                  'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
                  'onclick' => 'saveAndContinueEdit()',
                  'class' => 'save',
        ), -100);
	 }
	

	public function getInfoCache()
	{
		
		return Mage::getModel('perftestmanager/infosystem')->getCacheInfo();

	}

	public function getNodeIp()
	{
		return Mage::getModel('perftestmanager/infosystem')->getNodeIp();
	}

	public function getSessionInfo()
	{
		return Mage::getModel('perftestmanager/infosystem')->getSessionInfo();
	}

	public function getUptime()
	{
		return Mage::getModel('perftestmanager/infosystem')->getUptime();
	}

	public function getLoadAvg()
	{
		return Mage::getModel('perftestmanager/infosystem')->getLoadAvg();
	}

	public function getInfoFs()
	{
		return Mage::getModel('perftestmanager/infosystem')->getInfoFs();
	}

	public function getRamUsageApache()
	{
		return Mage::getModel('perftestmanager/infosystem')->getRamUsage('apache2');
	}

	public function getRamUsagePhp()
	{
		return Mage::getModel('perftestmanager/infosystem')->getRamUsage('php');
	}

	public function getRamUsageNgnix()
	{
		return Mage::getModel('perftestmanager/infosystem')->getRamUsage('ngnix');
	}

	public function getRamUsageFastCgi()
	{
		return Mage::getModel('perftestmanager/infosystem')->getRamUsage('fastcgi');
	}

	public function getMemCacheStats()
	{	
		return Mage::getModel('perftestmanager/infosystem')->getMemCacheStats();
	}

	public function getRedisStats()
	{	
		return Mage::getModel('perftestmanager/infosystem')->getRedisStats();
	}
    
	public function getVmStat()
	{
		return Mage::getModel('perftestmanager/infosystem')->getVmStat();	
	}

	public function getTotalRamUsage()
	{
		return Mage::getModel('perftestmanager/infosystem')->getTotalRamUsage();	
	}
}