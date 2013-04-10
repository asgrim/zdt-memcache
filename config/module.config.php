<?php

return array(
    'service_manager' => array(
        'invokables' => array(
            'ZdtMemcache\Collector\Collector' => 'ZdtMemcache\Collector\Collector',
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
