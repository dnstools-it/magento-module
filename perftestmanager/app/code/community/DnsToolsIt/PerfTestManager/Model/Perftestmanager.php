<?php
class DnsToolsIt_PerfTestManager_Model_PerfTestManager extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('perftestmanager/perftestmanager');
    }
}
