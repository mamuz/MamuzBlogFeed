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
            'MamuzBlogFeed\Listener\HeadLinkAggregate'    => 'MamuzBlogFeed\Listener\HeadLinkAggregateFactory',
            'MamuzBlogFeed\Feed\Writer\Factory'           => 'MamuzBlogFeed\Feed\WriterFactory',
            'MamuzBlogFeed\Options\ConfigProvider'        => 'MamuzBlogFeed\Options\ConfigProviderFactory',
        ),
    ),
    'view_manager'       => array(
        'strategies' => array('ViewFeedStrategy'),
    ),
    'MamuzBlogFeed'      => array(
        'default' => array(
            'autoHeadLink'  => true,
            'type'          => 'rss',
            'maxResults'    => 100,
            'language'      => 'en',
            'dateCreated'   => new \DateTime('2015-01-01'),
            'lastBuildDate' => new \DateTime,
            'title'         => 'My feed title',
            'description'   => 'My feed description',
            'encoding'      => 'UTF-8',
            /* OPTIONAL
            'image'         => array(
                'uri'         => '',
                'title'       => '', // OPTIONAL
                'link'        => '', // OPTIONAL
                'width'       => '', // OPTIONAL only rss
                'height'      => '', // OPTIONAL only rss
                'description' => '', // OPTIONAL only rss
            ),
            'categories'    => array(
                array(
                    'term'   => '', // machine readable category name
                    'scheme' => '', // OPTIONAL: The Atom scheme or RSS domain of  a category must be a valid URI
                ),
            ),
            'authors'       => array(
                array(
                    'name'  => '',
                    'email' => '', // OPTIONAL
                    'uri'   => '', // OPTIONAL
                ),
            ),
            'copyright'     => '',
            */
        ),
    ),
);
