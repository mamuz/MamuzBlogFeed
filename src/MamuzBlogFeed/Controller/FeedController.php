<?php

namespace MamuzBlogFeed\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model;

class FeedController extends AbstractActionController
{
    /**
     * @return Model\ModelInterface
     */
    public function postsAction()
    {
        $feedmodel = new Model\FeedModel();
        $feedmodel->setFeed($this->getPostsFeed()->render());

        return $feedmodel;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    private function getViewHelperManager()
    {
        $this->getServiceLocator()->get('ViewHelperManager');
    }

    /**
     * @return \MamuzBlogFeed\View\Helper\Feed
     */
    private function getPostsFeed()
    {
        return $this->getViewHelperManager()->get('postsFeed');
    }
}
