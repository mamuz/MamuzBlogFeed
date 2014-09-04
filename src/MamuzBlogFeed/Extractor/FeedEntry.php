<?php

namespace MamuzBlogFeed\Extractor;

use Doctrine\ORM\Query as QueryBuilder;
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

        $data = array(
            'id'           => $this->getRenderer()->hashId($object->getId()),
            'link'         => $this->getRenderer()->permaLinkPost($object),
            'title'        => $object->getTitle(),
            'description'  => $this->getRenderer()->markdown($object->getDescription()),
            'content'      => $this->getRenderer()->markdown($object->getContent()),
            'dateModified' => $object->getModifiedAt(),
            'dateCreated'  => $object->getCreatedAt(),
        );

        $categories = array();
        foreach ($object->getTags() as $tag) {
            $categories[] = array(
                'term'   => $tag->getName(),
                'scheme' => $this->getRenderer()->permaLinkTag($tag->getName()),
            );
        }

        if (!empty($categories)) {
            $data['categories'] = $categories;
        }

        return $data;
    }

    /**
     * @return RendererInterface | \MamuzBlog\View\Renderer\PhpRenderer
     */
    private function getRenderer()
    {
        return $this->renderer;
    }
}
