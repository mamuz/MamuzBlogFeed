<?php

namespace MamuzBlogFeed\Service;

use MamuzBlog\Mapper\Db\PostQuery as PostQueryMapper;
use MamuzBlog\Options\Range;
use MamuzBlog\Service\AbstractQueryFactory;
use MamuzBlog\Service\PostQuery;

class PostQueryFactory extends AbstractQueryFactory
{
    /**
     * @return \MamuzBlog\Feature\PostQueryInterface
     */
    public function createQueryService()
    {
        $queryMapper = new PostQueryMapper($this->getEntityManager(), new Range(100));
        $queryService = new PostQuery($queryMapper);

        return $queryService;
    }
}
