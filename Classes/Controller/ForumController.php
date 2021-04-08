<?php

namespace Karavas\AkForum\Controller;


use Karavas\AkForum\Domain\Repository\ForumRepository;
use Karavas\AkForum\Domain\Repository\FrontendUserRepository;
use Karavas\AkForum\Domain\Repository\PostRepository;
use Karavas\AkForum\Domain\Repository\ReplyRepository;
use Karavas\AkForum\Helper\Helper;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Context\Context;
use Psr\Http\Message\ResponseInterface;

/***
 *
 * This file is part of the "Just a forum" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Aristeidis Karavas <aristeidis.karavas@gmail.com>, Karavas
 *
 ***/

/**
 * ForumController
 */
class ForumController extends ActionController
{

    /**
     * forumRepository
     *
     * @var ForumRepository
     */
    protected $forumRepository;

    /**
     * postRepository
     *
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * replyRepository
     *
     * @var ReplyRepository
     */
    protected $replyRepository;

    /**
     * ForumController constructor.
     * @param Helper $helper
     * @param FrontendUserRepository $userRepository
     * @param SiteFinder $siteFinder
     * @param UriBuilder $uriBuilder
     * @param ForumRepository $forumRepository
     * @param PostRepository $postRepository
     * @param ReplyRepository $replyRepository
     * @throws AspectNotFoundException
     */
    public function __construct(
        Helper $helper,
        FrontendUserRepository $userRepository,
        SiteFinder $siteFinder,
        UriBuilder $uriBuilder,
        ForumRepository $forumRepository,
        PostRepository $postRepository,
        ReplyRepository $replyRepository
    )
    {
        parent::__construct($helper, $userRepository, $siteFinder, $uriBuilder);
        $this->forumRepository = $forumRepository;
        $this->postRepository = $postRepository;
        $this->replyRepository = $replyRepository;
    }

    /**
     * action show
     *
     * @return ResponseInterface
     */
    public function showAction(): ResponseInterface
    {
        $args = $this->request->getArguments();
        $forumId = $args['forumUid'];
        if ($forumId) {
            $forum = $this->forumRepository->findByUid($forumId);
        } else {
            $defaultForumId = (int)$this->settings['forum']['global']['defaultForumUId'];
            $forum = $this->forumRepository->findByUid($defaultForumId);
        }
        $breadcrumbItems = [
            $forum->getForumTitle() => $this->forumUrlDetail
        ];
        $this->view->assignMultiple([
            'forum' => $forum,
            'breadcrumb' => $breadcrumbItems
        ]);

        return $this->htmlResponse();
    }

    /**
     * Gets the latest posts
     * @return ResponseInterface
     */
    public function postFeedAction(): ResponseInterface
    {
        $postFeedLimit = $this->settings['forum']['global']['feed']['posts']['limit'];
        $this->postRepository->setDefaultOrderings(['crdate' => QueryInterface::ORDER_DESCENDING]);
        $posts = $this->postRepository->findAll()->getQuery()->setLimit((int)$postFeedLimit)->execute();

        $this->view->assign('feedPosts', $posts);

        return $this->htmlResponse();
    }

    /**
     * Gets the latest replies
     * @return ResponseInterface
     */
    public function replyFeedAction(): ResponseInterface
    {
        $repliesFeedLimit = $this->settings['forum']['global']['feed']['replies']['limit'];
        $this->replyRepository->setDefaultOrderings(['crdate' => QueryInterface::ORDER_DESCENDING]);
        $replies = $this->replyRepository->findAll()->getQuery()->setLimit((int)$repliesFeedLimit)->execute();

        $this->view->assign('feedReplies', $replies);

        return $this->htmlResponse();
    }
}
