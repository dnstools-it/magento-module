<?php
class DnsToolsIt_PerfTestManager_Helper_Data extends Mage_Core_Helper_Abstract
{

	protected function ByteSize($bytes) 
    {
	    $size = $bytes / 1024;
	    if($size < 1024)
	        {
	        $size = number_format($size, 2);
	        $size .= ' KB';
	        } 
	    else 
	        {
	        if($size / 1024 < 1024) 
	            {
	            $size = number_format($size / 1024, 2);
	            $size .= ' MB';
	            } 
	        else if ($size / 1024 / 1024 < 1024)  
	            {
	            $size = number_format($size / 1024 / 1024, 2);
	            $size .= ' GB';
	            } 
	        }
	    return $size;
    }


}
