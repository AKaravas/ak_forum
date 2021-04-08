<?php

namespace Karavas\AkForum\Controller;


use Karavas\AkForum\Domain\Model\Thema;
use Karavas\AkForum\Domain\Model\Topic;
use Karavas\AkForum\Domain\Repository\ForumRepository;
use Karavas\AkForum\Domain\Repository\FrontendUserRepository;
use Karavas\AkForum\Domain\Repository\ThemaRepository;
use Karavas\AkForum\Domain\Repository\TopicRepository;
use Karavas\AkForum\Helper\Helper;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Psr\Http\Message\ResponseInterface;

/***
 *
 * This file is part of the "Just a forum" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Aristeidis Karavas <aristeidis.karavas@gmail.com>, Karavas
 *
 ***/

/**
 * ThemaController
 */
class ThemaController extends ActionController
{

    /**
     * themaRepository
     *
     * @var ThemaRepository
     */
    protected $themaRepository;

    /**
     * topicRepository
     *
     * @var TopicRepository
     */
    protected $topicRepository;

    /**
     * forumRepository
     *
     * @var ForumRepository
     */
    protected $forumRepository;

    /**
     * ThemaController constructor.
     * @param Helper $helper
     * @param FrontendUserRepository $userRepository
     * @param SiteFinder $siteFinder
     * @param UriBuilder $uriBuilder
     * @param ThemaRepository $themaRepository
     * @param TopicRepository $topicRepository
     * @param ForumRepository $forumRepository
     * @throws AspectNotFoundException
     */
    public function __construct(
        Helper $helper,
        FrontendUserRepository $userRepository,
        SiteFinder $siteFinder,
        UriBuilder $uriBuilder,
        ThemaRepository $themaRepository,
        TopicRepository $topicRepository,
        ForumRepository $forumRepository
    )
    {
        parent::__construct($helper, $userRepository, $siteFinder, $uriBuilder);
        $this->themaRepository = $themaRepository;
        $this->topicRepository = $topicRepository;
        $this->forumRepository = $forumRepository;
    }

    /**
     * action show
     *
     * @param Thema $thema
     * @return ResponseInterface
     */
    public function showAction(Thema $thema): ResponseInterface
    {
        $topic = $this->topicRepository->findByUid($thema->getTopic());
        $forum = $this->forumRepository->findByUid($topic->getForum());
        $themaUri = $this->uriBuilder
            ->reset()
            ->setTargetPageUid($this->settings['forum']['global']['thema']['showPid'])
            ->uriFor('show', ['thema' => $thema->getUid()], 'Thema');
        $topicUri = $this->uriBuilder
            ->reset()
            ->setTargetPageUid($this->settings['forum']['global']['topic']['showPid'])
            ->uriFor('show', ['topic' => $topic->getUid()], 'Topic');

        if ($this->topicUrlList) {
            $breadcrumbItems = [
                LocalizationUtility::translate('action_topic_list', 'AkForum') => $this->topicUrlList,
                $topic->getName() => $topicUri,
                $thema->getName() => $themaUri
            ];
        } else {
            $breadcrumbItems = [
                $forum->getForumTitle() => $this->forumUrlDetail,
                $topic->getName() => $topicUri,
                $thema->getName() => $themaUri
            ];
        }
        $pinned = array();
        $featured = array();
        foreach ($thema->getPostsRel() as $post) {
            if ($post->getPinned()) {
                $pinned[] = $post;
            }
            if ($post->getFeatured()) {
                $featured[] = $post;
            }
        }

        foreach ($thema->getFollowers() as $follower) {
            if ($follower->getUid() === $this->userId) {
                $this->view->assign('isFollowing', 1);
            }
        }

        $this->view->assignMultiple([
            'thema' => $thema,
            'breadcrumb' => $breadcrumbItems,
            'pinned' => $pinned,
            'featured' => $featured,
            'userUid' => $this->userId
        ]);

        return $this->htmlResponse();
    }
}
