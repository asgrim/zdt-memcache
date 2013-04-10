<?php

namespace ZdtMemcache\Collector;

use ZendDeveloperTools\Collector\AbstractCollector;
use ZendDeveloperTools\Collector\AutoHideInterface;
use Zend\Mvc\MvcEvent;

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

        $this->data['summary'] = 'Memcached, yay';
		$this->data['test'] = 'foo';
	}

	public function canHide()
	{
		return false;
	}

	public function getSummary()
	{
		return $this->data['summary'];
	}

	public function getTest()
	{
		return $this->data['test'];
	}
}