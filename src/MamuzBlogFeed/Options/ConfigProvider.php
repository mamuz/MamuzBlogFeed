<?php

namespace MamuzBlogFeed\Options;

use MamuzBlogFeed\Filter\AbstractTagParamAware as FilterInterface;

class ConfigProvider implements ConfigProviderInterface
{
    /** @var string */
    private $defaultIndex;

    /** @var array */
    private $config = array();

    /** @var FilterInterface */
    private $optionsFilter;

    /**
     * @param array           $config
     * @param FilterInterface $optionsFilter
     * @param string          $defaultIndex
     */
    public function __construct(array $config, FilterInterface $optionsFilter, $defaultIndex = 'default')
    {
        $this->config = $config;
        $this->optionsFilter = $optionsFilter;
        $this->defaultIndex = (string) $defaultIndex;
    }

    public function getFor($index = null)
    {
        $tagParam = $index;
        if (!is_string($index) || !isset($this->config[$index])) {
            $index = $this->defaultIndex;
        }

        if (isset($this->config[$index])) {
            return $this->optionsFilter->setTagParam($tagParam)->filter($this->config[$index]);
        }

        return array();
    }
}
