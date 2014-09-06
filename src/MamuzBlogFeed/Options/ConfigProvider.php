<?php

namespace MamuzBlogFeed\Options;

class ConfigProvider implements ConfigProviderInterface
{
    /** @var string */
    private $defaultIndex;

    /** @var array */
    private $config = array();

    /**
     * @param array  $config
     * @param string $defaultIndex
     */
    public function __construct(array $config, $defaultIndex = 'default')
    {
        $this->config = $config;
        $this->defaultIndex = (string) $defaultIndex;
    }

    public function getFor($index = null)
    {
        if (!is_string($index) || !isset($this->config[$index])) {
            $index = $this->defaultIndex;
        }

        if (isset($this->config[$index])) {
            return (array) $this->config[$index];
        }

        return array();
    }
}
