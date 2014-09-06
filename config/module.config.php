<?php

return array(
    'router'             => array(
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
    'controllers'        => array(
        'factories' => array(
            'MamuzBlogFeed\Controller\Feed' => 'MamuzBlogFeed\Controller\FeedControllerFactory',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'feed' => 'MamuzBlogFeed\Controller\Plugin\FeedFactory',
        )
    ),
    'blog_domain'        => array(
        'factories' => array(
            'MamuzBlogFeed\Listener\QueryFilterAggregate' => 'MamuzBlogFeed\Listener\QueryFilterAggregateFactory',
            'MamuzBlogFeed\Feed\Writer\Factory'           => 'MamuzBlogFeed\Feed\WriterFactory',
            'MamuzBlogFeed\Options\ConfigProvider'        => 'MamuzBlogFeed\Options\ConfigProviderFactory',
        ),
    ),
    'view_manager'       => array(
        'strategies' => array('ViewFeedStrategy'),
    ),
    'MamuzBlogFeed'      => array(
        'default' => array(
            'maxResults'    => 100,
            'id'            => 'http://local.marco-muths.de/blog-feed',
            'type'          => 'rss',
            'copyright'     => 'mamuz',
            'language'      => 'de-DE',
            'dateCreated'   => new \DateTime,
            'dateModified'  => new \DateTime,
            'lastBuildDate' => new \DateTime,
            'title'         => 'mamuz feed',
            'description'   => 'description',
            'link'          => 'http://local.marco-muths.de/blog-feed',
            /*'encoding'      => '',
            'baseUrl'       => '',
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
            ),*/
        ),
    ),
);
