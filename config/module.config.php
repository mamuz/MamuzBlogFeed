<?php

return array(
    'router'        => array(
        'routes' => array(
            'blogFeedPosts' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/blog-feed[/:tag]',
                    'constraints' => array(
                        'tag' => '[a-zA-Z0-9_+%-]*',
                    ),
                    'defaults'    => array(
                        'controller' => 'MamuzBlogFeed\Controller\Feed',
                        'action'     => 'posts',
                    ),
                ),
            ),
        ),
    ),
    'controllers'   => array(
        'factories' => array(
            'MamuzBlogFeed\Controller\Feed' => 'MamuzBlogFeed\Controller\FeedControllerFactory',
        ),
    ),
    'blog_domain'   => array(
        'factories' => array(
            'MamuzBlogFeed\Service\PostQuery' => 'MamuzBlogFeed\Service\PostQueryFactory',
        ),
    ),
    'view_manager'  => array(
        'strategies' => array('ViewFeedStrategy'),
    ),
    'MamuzBlogFeed' => array(
        'postsFeed' => array(
            'type'          => 'rss',
            'id'            => '',
            'copyright'     => '',
            'language'      => '',
            'dateCreated'   => '',
            'dateModified'  => '',
            'lastBuildDate' => '',
            'title'         => '',
            'encoding'      => '',
            'baseUrl'       => '',
            'description'   => '',
            'link'          => '',
            'hubs'          => array(),
            'feedLinks'     => array(
                'rss'  => '',
                'rdf'  => '',
                'atom' => '',
            ),
            'image'         => array(
                'uri' => '',
            ),
            'generator'     => array(
                'name'    => '',
                'version' => '',
                'uri'     => '',
            ),
            'categories'    => array(
                array(
                    'term'   => '',
                    'scheme' => '',
                ),
            ),
            'authors'       => array(
                array(
                    'name'  => '',
                    'email' => '',
                    'uri'   => '',
                ),
            ),
        ),
    ),
);
