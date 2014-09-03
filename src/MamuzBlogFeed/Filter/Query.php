<?php

namespace MamuzBlogFeed\Filter;

use Doctrine\ORM\Query as QueryBuilder;
use Zend\Filter\FilterInterface;

class Query implements FilterInterface
{
    /** @var array */
    private $config = array();

    /**
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    public function filter($value)
    {
        if (!$value instanceof QueryBuilder) {
            return $value;
        }

        $tag = $value->getParameter('tag');
        if ($tag == null) {
            $tag = 'default';
        }

        if (isset($this->config[$tag]['maxResults'])) {
            $maxResults = (int) $this->config[$tag]['maxResults'];
        } else {
            $maxResults = 100;
        }

        $value->setFirstResult(0)->setMaxResults($maxResults);

        return $value;
    }
}
