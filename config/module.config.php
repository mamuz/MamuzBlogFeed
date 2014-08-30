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
    'MamuzBlogFeed' => array(
        'postsFeed' => array(
            'type' => 'rss',
            'id' => '',
            'copyright' => '',
            'language' => '',
            'dateCreated' => '',
            'dateModified' => '',
            'lastBuildDate' => '',
            'title' => '',
            'encoding' => '',
            'baseUrl' => '',
            'description' => '',
            'link' => '',
            'hubs' => array(),
            'feedLinks' => array(
                'rss' => '',
                'rdf' => '',
                'atom' => '',
            ),
            'image' => array(
                'uri' => '',
            ),
            'generator' => array(
                'name' => '',
                'version' => '',
                'uri' => '',
            ),
            'categories' => array(
                array(
                    'term' => '',
                    'scheme' => '',
                ),
            ),
            'authors' => array(
                array(
                    'name' => '',
                    'email' => '',
                    'uri' => '',
                ),
            ),
        ),
    ),
);
