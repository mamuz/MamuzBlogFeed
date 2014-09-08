<?php

namespace MamuzBlogFeed\Filter;

use Doctrine\ORM\Query as QueryBuilder;
use MamuzBlogFeed\Options\ConfigProviderInterface;

class Query extends AbstractTagParamAware
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

        $value->setFirstResult(0)->setMaxResults($this->getMaxResultsFromConfig());

        return $value;
    }

    /**
     * @return int
     */
    private function getMaxResultsFromConfig()
    {
        $config = $this->configProvider->getFor($this->getTagParam());

        if (isset($config['maxResults'])) {
            $maxResults = (int) $config['maxResults'];
        } else {
            $maxResults = $this->defaultMaxResults;
        }

        return $maxResults;
    }
}
