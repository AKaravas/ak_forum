<?php

namespace Karavas\AkForum\Controller;


use http\Client\Curl\User;
use Karavas\AkForum\Domain\Repository\ActivityRepository;
use Karavas\AkForum\Domain\Repository\AwardRepository;
use Karavas\AkForum\Domain\Repository\ForumRepository;
use Karavas\AkForum\Domain\Repository\PostRepository;
use Karavas\AkForum\Domain\Repository\ReplyRepository;
use Karavas\AkForum\Helper\Helper;
use Karavas\AkForum\UserFunc\UserFunc;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
 * ForumController
 */
class UserController extends ActionController
{
    /**
     * @var QuerySettingsInterface
     */
    protected $querySettings;

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

    /***
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /***
     * @var Helper
     */
    protected $helper;

    /**
     * ForumController constructor.
     * @param ForumRepository $forumRepository
     * @param PostRepository $postRepository
     * @param ReplyRepository $replyRepository
     * @param PersistenceManager $persistenceManager
     * @param Helper $helper
     * @param AwardRepository $awardRepository
     * @param ActivityRepository $activityRepository
     */
    public function __construct(
        ForumRepository $forumRepository,
        PostRepository $postRepository,
        ReplyRepository $replyRepository,
        PersistenceManager $persistenceManager,
        Helper $helper,
        AwardRepository $awardRepository,
        ActivityRepository $activityRepository
    ) {
        parent::__construct();
        $this->forumRepository = $forumRepository;
        $this->postRepository = $postRepository;
        $this->replyRepository = $replyRepository;
        $this->persistenceManager = $persistenceManager;
        $this->helper = $helper;
        $this->awardRepository = $awardRepository;
        $this->activityRepository = $activityRepository;
    }

    /**
     * action show
     *
     * @return void
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function profileAction(): void
    {
        $args = $this->request->getArguments();
        $userId = $args['userId'];
        $user = $this->userRepository->findByUid((int)$userId);
        if ($user && $user !== $this->userInfo) {
            $allowedActivities = $user->getAllowedActivity();
            $ids = [];
            if (count($user->getVisitedBy()) > 0) {
                foreach ($user->getVisitedBy() as $visited) {
                    $ids[] = $visited->getUid();
                }
                if (!empty($this->userInfo->getUid())
                    && !in_array($this->userInfo->getUid(), $ids, true)) {
                    $user->addVisitedBy($this->userInfo);
                    $this->userRepository->update($user);
                    $this->persistenceManager->persistAll();
                }
            } else {
                $user->addVisitedBy($this->userInfo);
                $this->userRepository->update($user);
                $this->persistenceManager->persistAll();
            }
            if ((int)$this->settings['forum']['global']['awards']['all']
                || ((int)$this->settings['forum']['global']['awards']['all'] !== 1
                    && (int)$this->settings['forum']['global']['awards']['profileViews'])) {
                $profileViewAward = $this->awardRepository->findAllByAwardId(999993);
                $visitedUsers = count($user->getVisitedBy());
                $this->helper->manageAwards($profileViewAward, $visitedUsers, $user,999993, 'profileViews', $this->settings);
            }
            $persistenceId = $this->settings['forum']['global']['post']['persistenceId'];
            if ((int)$this->settings['forum']['global']['activity']['all']
                || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                    && (int)$this->settings['forum']['global']['activity']['visitedUser'])) {
                $activity = $this->activityRepository->visitedUser($this->userId, $user->getUid(), 'VisitedUser');
                $this->helper->manageActivity('', $persistenceId, 'Visited the user ' . $user->getUsername(),
                    'VisitedUser', $this->userInfo, $activity, null, null, null, null, $user);
            }
        } elseif ($user && $user === $this->userInfo) {
            $user = $this->userInfo;
            $allowedActivities = $this->userInfo->getAllowedActivity();
        }else {
            $user = $this->userInfo;
            $allowedActivities = $this->userInfo->getAllowedActivity();
        }
        $activities = [];
        foreach ($user->getActivity() as $singleActivity) {
            if ((int)$this->settings['forum']['global']['user']['settings']['activated']
                && (int)$this->settings['forum']['global']['user']['settings']['activity'] ) {
                if (in_array(lcfirst($singleActivity->getTemplate()), $allowedActivities, true)) {
                    $activities[$singleActivity->getTstamp()] = $singleActivity;
                }
            }
            else {
                $activities[$singleActivity->getTstamp()] = $singleActivity;
            }
        }
        rsort($activities);

        $this->view->assignMultiple([
            'user' => $user,
            'activities' => $activities
        ]);
    }

    /**
     *
     */
    public function userSettingsAction(): void
    {
        $args = $this->request->getArguments();
        if ($args['userSettings'])
        {
            $this->userInfo->setAllowedActivity($args['userSettings']['allowedActivity']);
            $this->userRepository->update($this->userInfo);
            $this->persistenceManager->persistAll();
        }
        $selectedActivities = $this->userInfo->getAllowedActivity();
        $allActivities = $this->settings['forum']['global']['activity'];
        $activities =[];
        foreach ($allActivities as $key => $activity) {
            if ($key === 'all') {
                continue;
            }
            $activities[] = [
                'value' => $key,
                'checked' => (in_array($key, $selectedActivities, true) ? 'checked' : false),
                'title' => LocalizationUtility::translate('allowed_activity.'.$key, 'AkForum')
            ];
        }
        if ($this->isLoggedIn && $this->userInfo) {
            $this->view->assignMultiple([
                'user' => $this->userInfo,
                'activities' => $activities
            ]);
        }
    }
}
