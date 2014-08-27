<?php

return array(
    'router'           => array(
        'routes' => array(),
    ),
    'controllers'      => array(
        'factories' => array(),
    ),
    'service_manager'  => array(
        'factories' => array(),
    ),
    'blog_feed_domain' => array(
        'factories' => array(),
    ),
    'view_manager'     => array(
        'template_map'        => include __DIR__ . '/../template_map.php',
        'template_path_stack' => array(__DIR__ . '/../view'),
        'strategies'          => array('ViewFeedStrategy'),
    ),
);
