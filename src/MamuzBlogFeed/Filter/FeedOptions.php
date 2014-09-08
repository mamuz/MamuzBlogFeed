<?php

namespace MamuzBlogFeed\Filter;

use Zend\View\Renderer\RendererInterface;

class FeedOptions extends AbstractTagParamAware
{

    /** @var RendererInterface */
    private $renderer;

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
