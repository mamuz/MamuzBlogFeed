<?php

namespace MamuzBlogFeed\Extractor;

use MamuzBlog\Entity\Post;
use Zend\Stdlib\Extractor\ExtractionInterface;
use Zend\View\Renderer\RendererInterface;

class FeedEntry implements ExtractionInterface
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

    public function extract($object)
    {
        if (!$object instanceof Post) {
            return array();
        }

        $data = $this->getArrayCopyFrom($object);

        $categories = $this->extractCategoriesFrom($object);
        if (!empty($categories)) {
            $data['categories'] = $categories;
        }

        return $data;
    }

    /**
     * @param Post $object
     * @return array
     */
    private function getArrayCopyFrom(Post $object)
    {
        /** @var \MamuzBlog\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->renderer;

        $permaLink = $renderer->permaLinkPost($object);

        $data = array(
            'id'           => $permaLink,
            'link'         => $permaLink,
            'title'        => $object->getTitle(),
            'description'  => $renderer->markdown($object->getDescription()),
            'content'      => $renderer->markdown($object->getContent()),
            'dateModified' => $object->getModifiedAt(),
            'dateCreated'  => $object->getCreatedAt(),
        );

        return $data;
    }

    /**
     * @param Post $object
     * @return array
     */
    private function extractCategoriesFrom(Post $object)
    {
        /** @var \MamuzBlog\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->renderer;

        $categories = array();
        foreach ($object->getTags() as $tag) {
            $categories[] = array(
                'term'   => $tag->getName(),
                'scheme' => $renderer->permaLinkTag($tag->getName()),
            );
        }

        return $categories;
    }
}
