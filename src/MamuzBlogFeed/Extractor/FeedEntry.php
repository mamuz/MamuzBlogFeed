<?php

namespace MamuzBlogFeed\Extractor;

use MamuzBlog\Entity\Post;
use Zend\Stdlib\Extractor\ExtractionInterface;
use Zend\View\Renderer\RendererInterface;

class FeedEntry implements ExtractionInterface
{
    /** @var RendererInterface */
    private $renderer = array();

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

        return $this->getArrayCopy($object);
    }

    /**
     * @param Post $object
     * @return array
     */
    private function getArrayCopy(Post $object)
    {
        /** @var \MamuzBlog\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->renderer;

        $data = array(
            'id'           => $renderer->hashId($object->getId()),
            'link'         => $renderer->permaLinkPost($object),
            'title'        => $object->getTitle(),
            'description'  => $renderer->markdown($object->getDescription()),
            'content'      => $renderer->markdown($object->getContent()),
            'dateModified' => $object->getModifiedAt(),
            'dateCreated'  => $object->getCreatedAt(),
        );

        $categories = array();
        foreach ($object->getTags() as $tag) {
            $categories[] = array(
                'term'   => $tag->getName(),
                'scheme' => $renderer->permaLinkTag($tag->getName()),
            );
        }

        if (!empty($categories)) {
            $data['categories'] = $categories;
        }

        return $data;
    }
}
