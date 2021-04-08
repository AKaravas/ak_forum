<?php

namespace Karavas\AkForum\Ajax;

use Karavas\AkForum\Domain\Model\FrontendUser;
use Karavas\AkForum\Domain\Repository\ActivityRepository;
use Karavas\AkForum\Domain\Repository\AwardRepository;
use Karavas\AkForum\Domain\Repository\FrontendUserRepository;
use Karavas\AkForum\Domain\Repository\PostRepository;
use Karavas\AkForum\Domain\Repository\ReactionrelRepository;
use Karavas\AkForum\Domain\Repository\ReactionRepository;
use Karavas\AkForum\Domain\Repository\ReplyRepository;
use Karavas\AkForum\Domain\Repository\ThemaRepository;
use Karavas\AkForum\Helper\Helper;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;

class AjaxRequests
{
    /**
     * @var QuerySettingsInterface
     */
    protected $querySettings;

    /**
     * @var FrontendUser
     */
    protected $userInfo;

    /**
     * it is used to evaluate multiple things
     *
     * @var bool
     */
    protected $isLoggedIn = false;

    /**
     * it is used to evaluate multiple things
     *
     * @var int
     */
    protected $userId = 0;

    /**
     * @var JsonView
     */
    protected $view;

    /***
     * @var PostRepository
     */
    protected $postRepository;

    /***
     * @var ThemaRepository
     */
    protected $themaRepository;

    /***
     * @var ReactionRepository
     */
    protected $reactionRepository;

    /***
     * @var ReplyRepository
     */
    protected $replyRepository;

    /***
     * @var AwardRepository
     */
    protected $awardRepository;

    /**
     * activityRepository
     *
     * @var ActivityRepository
     */
    protected $activityRepository;

    /***
     * @var ReactionrelRepository
     */
    protected $reactionrelRepository;

    /***
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var string
     */
    protected $defaultViewObjectName = JsonView::class;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var array
     */
    protected $settings;

    /**
     * AjaxController constructor.
     * @param PostRepository $postRepository
     * @param PersistenceManager $persistenceManager
     * @param ThemaRepository $themaRepository
     * @param ReactionrelRepository $reactionrelRepository
     * @param ReactionRepository $reactionRepository
     * @param ReplyRepository $replyRepository
     * @param AwardRepository $awardRepository
     * @param ActivityRepository $activityRepository
     * @param Helper $helper
     * @throws AspectNotFoundException
     */
    public function __construct(
        PostRepository $postRepository,
        PersistenceManager $persistenceManager,
        ThemaRepository $themaRepository,
        ReactionrelRepository $reactionrelRepository,
        ReactionRepository $reactionRepository,
        ReplyRepository $replyRepository,
        AwardRepository $awardRepository,
        ActivityRepository $activityRepository,
        Helper $helper
    ) {
        $this->postRepository = $postRepository;
        $this->persistenceManager = $persistenceManager;
        $this->themaRepository = $themaRepository;
        $this->reactionRepository = $reactionRepository;
        $this->reactionrelRepository = $reactionrelRepository;
        $this->replyRepository = $replyRepository;
        $this->awardRepository = $awardRepository;
        $this->activityRepository = $activityRepository;
        $this->helper = $helper;
        $this->settings = $this->helper->loadPluginSettings();
        $this->userId = $this->helper->getLoggedInfo()['userUId'];
        $this->isLoggedIn = $this->helper->getLoggedInfo()['isLoggedIn'];
        $this->userInfo = $this->helper->getLoggedInfo()['userInfo'];
    }

    /**
     * @param array $params
     * @return array[]
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function toggleFollow(array $params)
    {
        if (array_key_exists('post', $params)) {
            $objId = (int)$params['post'];
            $obj = $this->postRepository->findByUid($objId);
            $res = $this->manageFollower($obj, $this->postRepository);
            if ((int)$this->settings['forum']['global']['activity']['all']
                || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                    && (int)$this->settings['forum']['global']['activity']['followedPost'])) {
                $activity = $this->activityRepository->findByPostAndTemplate($this->userId, $objId, 'FollowedPost');
                $this->helper->manageActivity($res['action'], $this->settings['forum']['global']['post']['persistenceId'],
                    'Followed the post ' . $obj->getTitle, 'FollowedPost', $this->userInfo, $activity, $obj);
            }
        } else {
            $objId = (int)$params['thema'];
            $obj = $this->themaRepository->findByUid($objId);
            $res = $this->manageFollower($obj, $this->themaRepository);
            if ((int)$this->settings['forum']['global']['activity']['all']
                || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                    && (int)$this->settings['forum']['global']['activity']['followedThema'])) {
                $activity = $this->activityRepository->findByThemaAndTemplate($this->userId, $objId, 'FollowedThema');
                $this->helper->manageActivity($res['action'], $this->settings['forum']['global']['post']['persistenceId'],
                    'Followed the thema ' . $obj->getName, 'FollowedThema', $this->userInfo, $activity, null, null,
                    $obj);
            }
        }
        return [
            'toggleFollower' => [
                'count' => $res['count'],
                'action' => $res['action']
            ]
        ];
    }

    /**
     * @param $object
     * @param $repository
     * @return array
     */
    public function manageFollower($object, $repository): array
    {
        $ids = [];
        if (count($object->getFollowers()) === 0) {
            $object->addFollower($this->userInfo);
            $repository->update($object);
            $this->persistenceManager->persistAll();
            $action = 'added';
        } else {
            foreach ($object->getFollowers() as $follower) {
                $ids[] = $follower->getUid();
            }
            if (!empty($this->userInfo->getUid()) && !in_array($this->userInfo->getUid(), $ids, true)) {
                $object->addFollower($this->userInfo);
                $repository->update($object);
                $this->persistenceManager->persistAll();
                $action = 'added';
            } else {
                $object->removeFollower($this->userInfo);
                $repository->update($object);
                $this->persistenceManager->persistAll();
                $action = 'removed';
            }
        }

        return [
            'count' => count($object->getFollowers()),
            'action' => $action
        ];
    }
}