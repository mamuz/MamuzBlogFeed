<?php

namespace MamuzBlogFeed\Filter;

use Doctrine\ORM\Query as QueryBuilder;
use Doctrine\ORM\Query\Parameter;
use MamuzBlogFeed\Options\ConfigProviderInterface;
use Zend\Filter\FilterInterface;

class Query implements FilterInterface
{
    /** @var ConfigProviderInterface */
    private $configProvider;

    /** @var int */
    private $defaultMaxResults;

    /**
     * @param ConfigProviderInterface $configProvider
     * @param int                     $defaultMaxResults
     */
    public function __construct(ConfigProviderInterface $configProvider, $defaultMaxResults = 100)
    {
        $this->configProvider = $configProvider;
        $this->defaultMaxResults = $defaultMaxResults;
    }

    public function filter($value)
    {
        if (!$value instanceof QueryBuilder) {
            return $value;
        }

        $tag = $value->getParameter('tag');
        if ($tag instanceof Parameter) {
            $tag = $tag->getValue();
        }

        $maxResults = $this->getMaxResultsFor($tag);

        $value->setFirstResult(0)->setMaxResults($maxResults);

        return $value;
    }

    /**
     * @param null|string $tag
     * @return int
     */
    private function getMaxResultsFor($tag)
    {
        $config = $this->configProvider->getFor($tag);

        if (isset($config['maxResults'])) {
            $maxResults = (int) $config['maxResults'];
        } else {
            $maxResults = $this->defaultMaxResults;
        }

        return $maxResults;
    }
}
