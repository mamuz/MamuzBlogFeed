<?php

return array(
    'router'       => array(
        'routes' => array(),
    ),
    'view_manager' => array(
        'strategies' => array('ViewFeedStrategy'),
    ),
    'view_helpers' => array(
        'factories' => array(
            'postsFeed' => 'MamuzBlogFeed\View\Helper\PostsFeedFactory',
        ),
    ),
);
