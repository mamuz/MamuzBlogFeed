<?php

namespace MamuzBlogFeed\Options;

interface ConfigProviderInterface
{
    /**
     * @param mixed $index
     * @return array
     */
    public function getFor($index = null);
}
