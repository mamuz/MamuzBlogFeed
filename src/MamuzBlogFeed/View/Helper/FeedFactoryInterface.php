<?php

namespace MamuzBlogFeed\View\Helper;

interface FeedFactoryInterface
{
    /**
     * @param  string|null $tag
     * @return FeedInterface
     */
    public function create($tag = null);
}
