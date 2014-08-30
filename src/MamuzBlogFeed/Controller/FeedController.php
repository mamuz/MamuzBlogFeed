<?php

namespace MamuzBlogFeed\Controller;

use MamuzBlogFeed\View\Helper\FeedFactory;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model;

class FeedController extends AbstractActionController
{
    /** @var FeedFactory */
    private $feedFactory;

    public function __construct(FeedFactory $feedFactory)
    {
        $this->feedFactory = $feedFactory;
    }

    /**
     * @return Model\ModelInterface
     */
    public function postsAction()
    {
        $name = $this->params()->fromRoute('tag');
        $feedWriter = $this->feedFactory->create($name);

        $feedmodel = new Model\FeedModel;
        $feedmodel->setFeed($feedWriter->render());

        return $feedmodel;
    }
}
