<?php

namespace ZdtMemcache\Collector;

use ZendDeveloperTools\Collector\AbstractCollector;
use ZendDeveloperTools\Collector\AutoHideInterface;
use Zend\Mvc\MvcEvent;
use Memcache;

class Collector extends AbstractCollector implements AutoHideInterface
{
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

        $mc = new Memcache();
        $mc->addserver('localhost', 11211);

        $stats = $mc->getStats();

        $data = array();

        // Cache usage
        $data['cache_size'] = (int)$stats['limit_maxbytes'];
        $data['cache_used'] = (int)$stats['bytes'];
        $data['cache_free'] = $data['cache_size'] - $data['cache_used'];

        // Hits & misses
        $data['hits'] = (int)$stats['get_hits'];
        $data['misses'] = (int)$stats['get_misses'];
        $data['get_total'] = $data['hits'] + $data['misses'];

        // Raw data
        $data['raw'] = $stats;

        $this->data = $data;
    }

    public function canHide()
    {
        return false;
    }

    public function getStat($stat, $default = null)
    {
        if (isset($this->data[$stat]))
        {
            return $this->data[$stat];
        }
        else
        {
            return $default;
        }
    }

    public function getPercent($numerator, $denominator, $precision = 2)
    {
        $num = (float)$this->getStat($numerator, 0);
        $den = (float)$this->getStat($denominator, 0);

        if ($den > 0)
        {
            return number_format(($num / $den) * 100, $precision);
        }
        else
        {
            return 0;
        }
    }

    public function getByteStat($stat)
    {
        $s = (int)$this->getStat($stat, 0);

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
