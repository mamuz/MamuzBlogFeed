<?php

namespace MamuzBlogFeed\Filter;

use Zend\View\Renderer\RendererInterface;

class FeedOptions extends AbstractTagParamAware
{
    /** @var RendererInterface */
    private $renderer;

    /** @var string */
    private $selfUrl;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function filter($value)
    {
        if (!is_array($value)) {
            $value = (array) $value;
        }

        /** @var \MamuzBlog\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->renderer;

        if (!isset($value['id'])) {
            $value['id'] = $this->getSelfUrl();
        }
        if (!isset($value['feedUrl'])) {
            $value['feedUrl'] = $this->getSelfUrl();
        }
        if (!isset($value['link'])) {
            $value['link'] = $renderer->permaLinkTag($this->getTagParam());
        }
        if (!isset($value['baseUrl'])) {
            $value['baseUrl'] = $renderer->serverUrl();
        }

        return $value;
    }

    /**
     * @return string
     */
    private function getSelfUrl()
    {
        if (!is_string($this->selfUrl)) {
            $this->selfUrl = $this->createSelfUrl();
        }

        return $this->selfUrl;
    }

    /**
     * @return string
     */
    private function createSelfUrl()
    {
        /** @var \MamuzBlog\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->renderer;

        $serverUrl = $renderer->serverUrl();

        $url = $renderer->url(
            'blogFeedPosts',
            array('tag' => $this->getTagParam())
        );

        return $serverUrl . $url;
    }
}
