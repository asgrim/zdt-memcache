<?php

return array(
    'controllers' => array(
        'factories' => array(
            'ZdtMemcache\Controller\Index' => function($sm) {
                $mc = $sm->getServiceLocator()->get('ZdtMemcache\MemcachedResource');
                return new \ZdtMemcache\Controller\IndexController($mc);
            },
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'ZdtMemcache\MemcachedResource' => function($sm) {
                $mc = new Memcached();
                $mc->addServer('localhost', 11211);
                return $mc;
            },
            'ZdtMemcache\Collector\Collector' => function($sm) {
                $mc = $sm->get('ZdtMemcache\MemcachedResource');
                return new \ZdtMemcache\Collector\Collector($mc);
            },
        ),
    ),
    'router' => array(
        'routes' => array(
            'zdt_memcache' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/zdt_memcached_action/[:action]',
                    'defaults' => array(
                        'controller' => 'ZdtMemcache\Controller\Index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'zend-developer-tools/toolbar/zdt-memcache' => __DIR__ . '/../view/zend-developer-tools/toolbar/zdt-memcache.phtml',
        ),
    ),
    'zenddevelopertools' => array(
        'profiler' => array(
            'collectors' => array(
                'zdt-memcache'  => 'ZdtMemcache\Collector\Collector',
            ),
        ),
        'toolbar' => array(
            'entries' => array(
                'zdt-memcache'  => 'zend-developer-tools/toolbar/zdt-memcache',
            ),
        ),
    ),
);
