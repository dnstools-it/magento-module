<?php

class DnsToolsIt_PerfTestManager_Adminhtml_InfosystemController extends Mage_Adminhtml_Controller_Action

{
	public function indexAction()
	{
		$this->loadLayout();
		$block=$this->getLayout()->createBlock("perftestmanager/infosystem")->setTemplate("perftestmanager/infosystem.phtml");
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
}