<?php

namespace MamuzBlogFeed\Filter;

use Zend\Filter\FilterInterface;

abstract class AbstractTagParamAware implements FilterInterface
{
    /** @var string|null */
    private $tagParam = null;

    /**
     * @param null|string $tagParam
     * @return FeedOptions
     */
    public function setTagParam($tagParam)
    {
        $this->tagParam = is_null($tagParam) ? null : (string) $tagParam;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTagParam()
    {
        return $this->tagParam;
    }
}
