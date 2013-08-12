<?php

class DnsToolsIt_PerfTestManager_Model_Mysql4_Statsinfo_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('statsinfo/statsinfo');
    }
	
	public function addFieldToSelect($field,$alias=null)
	{
		if ($field === '*') { // If we will select all fields
            $this->_fieldsToSelect = null;
            $this->_fieldsToSelectChanged = true;
            return $this;
        }
	}
}

