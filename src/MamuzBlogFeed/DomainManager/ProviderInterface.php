<?php

namespace MamuzBlogFeed\DomainManager;

interface ProviderInterface
{
    /**
     * @return array
     */
    public function getBlogFeedDomainConfig();
}
