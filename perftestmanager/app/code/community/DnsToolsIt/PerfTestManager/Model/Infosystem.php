<?php
class DnsToolsIt_PerfTestManager_Model_Infosystem extends Mage_Core_Model_Abstract
{

	public function getCacheInfo()
    {
        $cache_dir=realpath(Mage::getBaseDir('base')."/var/cache/");
        $output = shell_exec("du -sh $cache_dir");
        return $output;
    }

    public function getNodeIp()
    {
    	$output = nl2br(shell_exec('/sbin/ifconfig'));
    	return $output;
    }

    public function getSessionInfo()
    {
        $session_dir=realpath(Mage::getBaseDir('base')."/var/session/");
        $output = shell_exec("du -sh $session_dir");
        return $output;
    }

    public function getUptime()
    {
    	$output = nl2br(shell_exec('uptime'));
    	return $output;
    }

    public function getLoadAvg()
    {
    	$output = nl2br(shell_exec('uptime'));
    	return $output;
    }

    public function getInfoFs()
    {
    	$output = nl2br(shell_exec('df -h'));
    	return $output;
    }

    public function getRamUsage($process)
    {
        $output = shell_exec("ps -o rss -C $process | tail -n +2 | (sed 's/^/x+=/'; echo x) | bc");
        return($output);
    }


    public function getVmStat()
    {
        $output = nl2br(shell_exec("vmstat"));
        return($output);
    }

    public function getTotalRamUsage()
    {
        $output = nl2br(shell_exec("free -mt"));
        return($output);
    }

     public function getMemCacheStats()
    {
        if (Mage::getConfig()->getNode('global/cache/backend')) 
        {
            
                $server = Mage::getConfig()->getNode('global/cache/memcached/servers/server/host');
                $port   = Mage::getConfig()->getNode('global/cache/memcached/servers/server/port');

                if (Mage::getConfig()->getNode('global/cache/backend')=='memcached')
                {
                    $output=$this->telnet((string)$server,(string)$port,'stats');
                }else{
                    $output="Memcached not in use";
                }
        }else{
            $output="Memcached not in use ";
        }
        return(nl2br($output));
    }

     public function getRedisStats()
    {
        if (Mage::getConfig()->getNode('global/cache/backend')) 
        {
                $server = Mage::getConfig()->getNode('global/cache/backend_options/server');
                $port   = Mage::getConfig()->getNode('global/cache/backend_options/port');
                
                
                if (Mage::getConfig()->getNode('global/cache/backend')=='Cm_Cache_Backend_Redis')
                {
                    $output=$this->telnet((string)$server,(string)$port,'INFO');
                }else{
                    $output="Redis not in use ";
                }
        }else{
            $output="Redis not in use ";
        }

        return(nl2br($output));
    }



    private function telnet($host,$port,$command)
    {
        $fp = fsockopen($host, $port, $errno, $errstr, 30);

        $output='';
        $done=false;
        $done2=false;

        if (!$fp) {

            $output = "$errstr ($errno)<br />\n";

        } else {
            sleep(1);
            $k=0;
            $res = fwrite($fp, $command."\r\n");
                while (  !feof( $fp ) && $k<8 ) {
                        $outputpart=fgets($fp,4096);
                        $output.=$outputpart;
                        $done  =(trim($outputpart)===''); //redis had have 9 section so $k<8
                        $done2 =(trim($outputpart)==='END');//memcached end with END
                        if ($done)
                                $k++;
                        if ($done2)
                                break;
                }

            fclose($fp);

        }


        return $output;

    }

}