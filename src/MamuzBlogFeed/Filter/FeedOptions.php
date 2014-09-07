<?php

namespace MamuzBlogFeed\Filter;

use Zend\Filter\FilterInterface;
use Zend\View\Renderer\RendererInterface;

class FeedOptions implements FilterInterface
{
    /** @var RendererInterface */
    private $renderer;

    /** @var string|null */
    private $tagParam = null;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

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

    public function filter($value)
    {
        if (!is_array($value)) {
            $value = (array) $value;
        }

        /** @var \MamuzBlog\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->renderer;

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
