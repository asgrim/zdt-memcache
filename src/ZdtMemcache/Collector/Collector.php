<?php

namespace ZdtMemcache\Collector;

use ZendDeveloperTools\Collector\AbstractCollector;
use ZendDeveloperTools\Collector\AutoHideInterface;
use Zend\Mvc\MvcEvent;
use Memcached;

class Collector extends AbstractCollector implements AutoHideInterface
{
    /**
     * @var \Memcached
     */
    protected $memcache;

    /**
     * @param \Memcached $memcached
     */
    public function __construct(Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    public function getName()
    {
        return "zdt-memcache";
    }

    public function getPriority()
    {
        return 10;
    }

    public function collect(MvcEvent $mvcEvent)
    {
        if (!isset($this->data)) {
            $this->data = array();
        }

        $allStats = $this->memcached->getStats();

        $data = array();

        foreach($allStats as $server => $stats)
        {
            // Cache usage
            $data[$server]['cache_size'] = (int)$stats['limit_maxbytes'];
            $data[$server]['cache_used'] = (int)$stats['bytes'];
            $data[$server]['cache_free'] = $data[$server]['cache_size'] - $data[$server]['cache_used'];

            // Hits & misses
            $data[$server]['hits'] = (int)$stats['get_hits'];
            $data[$server]['misses'] = (int)$stats['get_misses'];
            $data[$server]['get_total'] = $data[$server]['hits'] + $data[$server]['misses'];

            // Raw data
            $data[$server]['raw'] = $stats;
        }

        $this->data = $data;
    }

    public function getServers()
    {
        return array_keys($this->data);
    }

    public function canHide()
    {
        return false;
    }

    public function getStat($server, $stat, $default = null)
    {
        if (isset($this->data[$server][$stat]))
        {
            return $this->data[$server][$stat];
        }
        else
        {
            return $default;
        }
    }

    public function getPercent($server, $numerator, $denominator, $precision = 2)
    {
        $num = (float)$this->getStat($server, $numerator, 0);
        $den = (float)$this->getStat($server, $denominator, 0);

        if ($den > 0)
        {
            return number_format(($num / $den) * 100, $precision);
        }
        else
        {
            return 0;
        }
    }

    public function getByteStat($server, $stat)
    {
        $s = (int)$this->getStat($server, $stat, 0);

        foreach (array('','K','M','G') as $i => $k)
        {
            if ($s < 1024)
            {
                break;
            }
            $s /= 1024;
        }
        return sprintf("%.1f %sb", $s, $k);
    }
}
