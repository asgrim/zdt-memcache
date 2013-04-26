<?php

namespace ZdtMemcache\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Memcached;

class IndexController extends AbstractActionController
{
	/**
	 * @var \Memcached
	 */
	protected $memcached;

	/**
	 * @param \Memcached $memcache
	 */
	public function __construct(Memcached $memcached)
	{
		$this->memcached = $memcached;
	}

	public function flushAction()
	{
		#var_dump($this->memcached->getAllKeys());

		$result = $this->memcached->flush();

		#var_dump($this->memcached->getAllKeys());
		#die();

		if (!$result)
		{
			throw new \RuntimeException("Could not flush");
		}

		return $this->redirectToReferrer();
	}

	public function redirectToReferrer()
	{
		$redirectUrl = $this->getRequest()->getHeader('HTTP_REFERER', '/');
		#return $this->redirect()->toUrl($redirectUrl);
		header("Location: {$redirectUrl}");
		echo("OMGLOLS");
		die();
	}
}