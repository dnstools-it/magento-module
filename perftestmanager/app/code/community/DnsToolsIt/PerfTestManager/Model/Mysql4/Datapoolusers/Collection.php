<?php

class DnsToolsIt_PerfTestManager_Model_Mysql4_PerfTestManager_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('perftestmanager/perftestmanager');
    }
}

