<?php


namespace Karavas\AkForum\Controller;

use Karavas\AkForum\Domain\Model\FrontendUser;
use Karavas\AkForum\Domain\Model\Reactionrel;
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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class AjaxController
 * @package Karavas\AkForum\Controller
 */
class AjaxController extends ActionController
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
     * @var StandaloneView
     */
    protected $viewConfig;

    /**
     * @var array
     */
    protected $extbaseFrameworkConfiguration;

    /**
     * @var string
     */
    protected $defaultViewObjectName = JsonView::class;

    /**
     * AjaxController constructor.
     * @param Helper $helper
     * @param Context $context
     * @param FrontendUserRepository $userRepository
     * @param SiteFinder $siteFinder
     * @param UriBuilder $uriBuilder
     * @param PostRepository $postRepository
     * @param PersistenceManager $persistenceManager
     * @param ThemaRepository $themaRepository
     * @param ReactionrelRepository $reactionrelRepository
     * @param ReactionRepository $reactionRepository
     * @param ReplyRepository $replyRepository
     * @param AwardRepository $awardRepository
     * @param ActivityRepository $activityRepository
     * @throws AspectNotFoundException
     * @throws StopActionException
     */
    public function __construct(
        Helper $helper,
        Context $context,
        FrontendUserRepository $userRepository,
        SiteFinder $siteFinder,
        UriBuilder $uriBuilder,
        PostRepository $postRepository,
        PersistenceManager $persistenceManager,
        ThemaRepository $themaRepository,
        ReactionrelRepository $reactionrelRepository,
        ReactionRepository $reactionRepository,
        ReplyRepository $replyRepository,
        AwardRepository $awardRepository,
        ActivityRepository $activityRepository
    ) {
        parent::__construct($helper, $context, $userRepository, $siteFinder, $uriBuilder);
        $this->postRepository = $postRepository;
        $this->persistenceManager = $persistenceManager;
        $this->themaRepository = $themaRepository;
        $this->reactionRepository = $reactionRepository;
        $this->reactionrelRepository = $reactionrelRepository;
        $this->replyRepository = $replyRepository;
        $this->awardRepository = $awardRepository;
        $this->activityRepository = $activityRepository;
    }

    /**
     * Initialize Action
     * @throws StopActionException
     */
    public function initializeAction(): void
    {
        parent::initializeAction();
        $this->viewConfig = GeneralUtility::makeInstance(StandaloneView::class);
        $this->extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $this->viewConfig->setTemplateRootPaths($this->extbaseFrameworkConfiguration['view']['templateRootPaths']);
        $this->viewConfig->setLayoutRootPaths($this->extbaseFrameworkConfiguration['view']['layoutRootPaths']);
        $this->viewConfig->setPartialRootPaths($this->extbaseFrameworkConfiguration['view']['partialRootPaths']);

        $args = $this->request->getArguments();
        switch ($this->request->getControllerActionName()) {
            case 'quote':
                $this->evaluateParentChildHmac(
                    $args['postHmac'],
                    $args['replyHmac'],
                    $args['post'],
                    $args['reply']
                );
                break;
            case 'getCurrentCount':
            case 'removeCurrentQuotes':
                $this->evaluateHmac($args['hmac'], $args['post']);
                break;
            case 'votes':
            case 'reaction':
                if (array_key_exists('post', $args)) {
                    $this->evaluateHmac($args['hmac'], (int)$args['post']);
                } else {
                    $this->evaluateHmac($args['hmac'], (int)$args['reply']);
                }
                break;
        }
    }

    /**
     * votes action
     */
    public function quoteAction(): void
    {
        $args = $this->request->getArguments();
        $postId = (int)$args['post'];
        $replyId = (int)$args['reply'];
        $postInSession = $GLOBALS['TSFE']->fe_user->getKey('ses', 'post-' . $postId);
        if ($postInSession === null || empty($postInSession)) {
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'post-' . $postId, array($replyId => $replyId));
            $GLOBALS['TSFE']->fe_user->storeSessionData();
            $currentList = $GLOBALS['TSFE']->fe_user->getKey('ses', 'post-' . $postId);
        } else {
            $currentList = $GLOBALS['TSFE']->fe_user->getKey('ses', 'post-' . $postId);
            if (in_array($replyId, $currentList, true)) {
                unset($currentList[$replyId]);
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'post-' . $postId, $currentList);
                $GLOBALS['TSFE']->fe_user->storeSessionData();
            } else {
                $currentList[$replyId] = $replyId;
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'post-' . $postId, $currentList);
                $GLOBALS['TSFE']->fe_user->storeSessionData();
            }
        }
        if (count($currentList) > 0) {
            $counted = count($currentList);
        } else {
            $counted = null;
        }
        $results = [
            'quote' => [
                'count' => $counted,
                'list' => $currentList
            ]
        ];

        $counted = $results;

        $this->view->setVariablesToRender(['counted']);
        $this->view->assign('counted', $counted);
    }

    public function getCurrentCountAction(): void
    {
        $args = $this->request->getArguments();
        $postId = (int)$args['post'];
        $currentList = $GLOBALS['TSFE']->fe_user->getKey('ses', 'post-' . $postId);
        if ($currentList > 0) {
            $counted = count($currentList);
            if ($counted === 0) {
                $results = [
                    'quote' => [
                        'count' => null
                    ]
                ];
            } else {
                $results = [
                    'quote' => [
                        'count' => $counted,
                        'list' => $currentList
                    ]
                ];
            }
        } else {
            $results = [
                'quote' => [
                    'count' => null
                ]
            ];
        }

        $this->view->setVariablesToRender(['results']);
        $this->view->assign('results', $results);
    }

    public function removeCurrentQuotesAction(): void
    {
        $args = $this->request->getArguments();
        $postId = (int)$args['post'];
        $currentList = $GLOBALS['TSFE']->fe_user->getKey('ses', 'post-' . $postId);
        foreach ($currentList as $key => $reply) {
            unset($currentList[$key]);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'post-' . $postId, $currentList);
            $GLOBALS['TSFE']->fe_user->storeSessionData();
        }

        $results = [
            'quote' => [
                'count' => null
            ]
        ];

        $this->view->setVariablesToRender(['results']);
        $this->view->assign('results', $results);
    }

    /**
     * @param array $args
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function toggleFollowAction(array $args): void
    {
        if (array_key_exists('post', $args)) {
            $objId = (int)$args['post'];
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
            $objId = (int)$args['thema'];
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
        $results = [
            'toggleFollower' => [
                'count' => $res['count'],
                'action' => $res['action']
            ]
        ];

        $this->view->setVariablesToRender(['results']);
        $this->view->assign('results', $results);
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

    /**
     * reaction Action
     *
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function reactionAction(): void
    {
        if ($this->settings['forum']['global']['system'] === 'reaction')
        {
            $args = $this->request->getArguments();

            $reactionUid = $args['reaction'];
            $reaction = $this->reactionRepository->findByUid((int)$reactionUid);
            $obj = null;
            if (array_key_exists('post', $args)) {
                $objectName = 'post';
                $objId = (int)$args['post'];
                $obj = $this->postRepository->findByUid($objId);
                $reactionRel = $this->reactionrelRepository->findByUserAndPost($this->userInfo, $obj);

                $res = $this->manageReaction($obj, $this->postRepository, $reaction, $reactionRel);
                $reactionRel = $this->reactionrelRepository->findByUserAndPost($this->userInfo, $obj);

                if ((int)$this->settings['forum']['global']['activity']['all']
                    || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                        && (int)$this->settings['forum']['global']['activity']['reactedToPost'])) {
                    $activity = $this->activityRepository->findByPostAndTemplate($this->userId, $objId, 'ReactedToPost');
                    $this->helper->manageActivity($res['action'], $this->settings['forum']['global']['post']['persistenceId'],
                        'Reacted to post' . $obj->getTitle, 'ReactedToPost', $this->userInfo, $activity, $obj, null, null,
                        $reactionRel);
                }
            } else {
                $objectName = 'reply';
                $objId = (int)$args['reply'];
                $obj = $this->replyRepository->findByUid($objId);
                $reactionRel = $this->reactionrelRepository->findByUserAndReply($this->userInfo, $obj);

                $res = $this->manageReaction($obj, $this->replyRepository, $reaction, $reactionRel);
                $reactionRel = $this->reactionrelRepository->findByUserAndReply($this->userInfo, $obj);

                if ((int)$this->settings['forum']['global']['activity']['all']
                    || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                        && (int)$this->settings['forum']['global']['activity']['reactedToReply'])) {
                    $activity = $this->activityRepository->findByReplyAndTemplate($this->userId, $objId, 'ReactedToReply');
                    $this->helper->manageActivity($res['action'], $this->settings['forum']['global']['post']['persistenceId'],
                        'Reacted to reply' . $obj->getTitle, 'ReactedToReply', $this->userInfo, $activity, null, $obj, null,
                        $reactionRel);
                }
            }
            if ($this->settings['forum']['global']['reputation']['activated']) {
                $user = $obj->getCreatedBy();
                $reactionSum = $this->helper->calculateReputation($user, $this->settings);

                $user->setReputation($reactionSum);
                $this->userRepository->update($user);
                $this->persistenceManager->persistAll();
            }
            $results = [
                'reaction' => [
                    'reactionUid' => $reaction->getUid(),
                    'objectUid' => $res['objectUid'],
                    'object' => $objectName,
                    'action' => $res['action'],
                    'previousReaction' => $res['previousReaction'],
                    'userReputation' => $obj->getCreatedBy()->getReputation(),
                    'userId' => $obj->getCreatedBy()->getUid()
                ]
            ];
        }
        else {
            $results = [
                'message' => 'This action is not allowed by this forum'
            ];
        }

        $this->view->setVariablesToRender(['results']);
        $this->view->assign('results', $results);

    }

    /**
     * @param $obj
     * @param $repository
     * @param $reaction
     * @param $reactionRel
     * @return array
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function manageReaction($obj, $repository, $reaction, $reactionRel): array
    {
        $previousReaction = null;
        if ($reactionRel === null) {
            $reactionsRelModel = GeneralUtility::makeInstance(Reactionrel::class);
            $reactionsRelModel->setUser($this->userInfo);
            $reactionsRelModel->setReaction($reaction);
            $this->reactionrelRepository->add($reactionsRelModel);
            $this->persistenceManager->persistAll();

            $obj->addReactionRel($reactionsRelModel);
            $repository->update($obj);
            $this->persistenceManager->persistAll();
            $action = 'added';
        } elseif ($reaction->getUid() === $reactionRel->getReaction()->getUid()) {
            $obj->removeReactionRel($reactionRel);
            $repository->update($obj);
            $this->persistenceManager->persistAll();
            $action = 'removed';
        } else {
            $previousReaction = $reactionRel->getReaction()->getUid();
            $reactionRel->setReaction($reaction);
            $this->reactionrelRepository->update($reactionRel);
            $this->persistenceManager->persistAll();
            $action = 'added';
        }
        return [
            'objectUid' => $obj->getUid(),
            'previousReaction' => $previousReaction,
            'action' => $action
        ];
    }

    public function getReactionListAction(): void
    {
        $args = $this->request->getArguments();
        $this->viewConfig->setTemplate('Post/AjaxUsers.html');
        $reactions = $this->reactionRepository->findAll();
        $reactionCountedItems = [];
        $reactionNames = [];
        foreach ($reactions as $reaction) {
            $reactionNames[] = $reaction->getName();
        }

        foreach ($args as $key => $arg) {
            if ($key === 'post') {
                $objId = (int)$args['post'];
                $obj = $this->postRepository->findByUid($objId);
                $allCounted = count($obj->getReactionRel());
                foreach ($reactionNames as $reactionName) {
                    $reactionCountedItems[$reactionName] = count($this->reactionrelRepository->findReactionsByNamePost($obj,
                        $reactionName));
                }
            } elseif ($key === 'reply') {
                $objId = (int)$args['reply'];
                $obj = $this->replyRepository->findByUid($objId);
                $allCounted = count($obj->getReactionRel());
                foreach ($reactionNames as $reactionName) {
                    $reactionCountedItems[$reactionName] = count($this->reactionrelRepository->findReactionsByNameReply($obj,
                        $reactionName));
                }
            }
        }
        $this->viewConfig->assignMultiple([
            'object' => $obj,
            'settings' => $this->settings,
            'reactions' => $reactions,
            'countedItems' => $reactionCountedItems,
            'allCounted' => $allCounted
        ]);

        $content = [
            'reactionUsers' => $this->viewConfig->render()
        ];

        $this->view->assign('content', $content);
        $this->view->setVariablesToRender(['content']);
        $this->view->assign('content', $content);
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function votesAction(): void
    {
        if ($this->settings['forum']['global']['system'] === 'vote') {
            $args = $this->request->getArguments();
            if (array_key_exists('post', $args)) {
                $vote = $args['vote'];
                $objectId = (int)$args['post'];
                $objectType = 'post';
                $obj = $this->postRepository->findByUid($objectId);
                $status = $this->manageVotes($obj, $this->postRepository, $vote);
                $newResults = $this->getVoteResults($obj, 'post');
                if ((int)$this->settings['forum']['global']['activity']['all']
                    || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                        && (int)$this->settings['forum']['global']['activity']['votedPost'])) {
                    $activity = $this->activityRepository->findByPostAndTemplate($this->userId, $objectId, 'VotedPost');
                    $this->helper->manageActivity($status, $this->settings['forum']['global']['post']['persistenceId'],
                        'Voted the post' . $obj->getTitle, 'VotedPost', $this->userInfo, $activity, $obj);
                }
            } else {
                $vote = $args['vote'];
                $objectId = (int)$args['reply'];
                $objectType = 'reply';
                $obj = $this->replyRepository->findByUid($objectId);
                $status = $this->manageVotes($obj, $this->replyRepository, $vote);
                $newResults = $this->getVoteResults($obj, 'reply');
                if ((int)$this->settings['forum']['global']['activity']['all']
                    || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                        && (int)$this->settings['forum']['global']['activity']['votedReply'])) {
                    $activity = $this->activityRepository->findByReplyAndTemplate($this->userId, $objectId,
                        'VotedReply');
                    $this->helper->manageActivity($status, $this->settings['forum']['global']['post']['persistenceId'],
                        'Voted the post' . $obj->getTitle, 'VotedReply', $this->userInfo, $activity, null, $obj);
                }
            }
            $results = [
                'vote' => [
                    'status' => $status,
                    'newResults' => $newResults['count'],
                    'message' => $newResults['message'],
                    'object' => $objectType,
                    'objectUid' => $objectId,
                    'userReputation' => $obj->getCreatedBy()->getReputation(),
                    'userId' => $obj->getCreatedBy()->getUid()
                ]
            ];
        }
        else {
            $results = [
                'message' => 'This action is not allowed by this forum'
            ];
        }
        $this->view->setVariablesToRender(['results']);
        $this->view->assign('results', $results);
    }

    /**
     * @param $obj
     * @param $repository
     * @param $vote
     * @return string
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function manageVotes($obj, $repository, $vote): string
    {
        $status = '';
        if ($vote === 'upvote') {
            $isDownVoted = $this->checkVotedStatus($obj->getDownvotedBy());
            $isUpvoted = $this->checkVotedStatus($obj->getUpvotedBy());
            if ($isUpvoted !== true && $isDownVoted !== true) {
                $obj->addUpvotedBy($this->userInfo);
                $repository->update($obj);
                $this->persistenceManager->persistAll();
                $status = 'upVoted';
            } elseif ($isUpvoted && $isDownVoted !== true) {
                $obj->removeUpvotedBy($this->userInfo);
                $repository->update($obj);
                $this->persistenceManager->persistAll();
                $status = 'no-vote';
            } elseif ($isUpvoted !== true && $isDownVoted === true) {
                $obj->removeDownvotedBy($this->userInfo);
                $repository->update($obj);
                $this->persistenceManager->persistAll();
                $obj->addUpvotedBy($this->userInfo);
                $repository->update($obj);
                $this->persistenceManager->persistAll();
                $status = 'upVoted';
            }
        } else {
            $isDownVoted = $this->checkVotedStatus($obj->getDownvotedBy());
            $isUpvoted = $this->checkVotedStatus($obj->getUpvotedBy());
            if ($isUpvoted !== true && $isDownVoted !== true) {
                $obj->addDownvotedBy($this->userInfo);
                $repository->update($obj);
                $this->persistenceManager->persistAll();
                $status = 'downVoted';
            } elseif ($isUpvoted !== true && $isDownVoted) {
                $obj->removeDownvotedBy($this->userInfo);
                $repository->update($obj);
                $this->persistenceManager->persistAll();
                $status = 'no-vote';
            } elseif ($isUpvoted && $isDownVoted !== true) {
                $obj->removeUpvotedBy($this->userInfo);
                $repository->update($obj);
                $this->persistenceManager->persistAll();
                $obj->addDownvotedBy($this->userInfo);
                $repository->update($obj);
                $this->persistenceManager->persistAll();
                $status = 'downVoted';
            }
        }

        if ($this->settings['forum']['global']['system'] === 'vote' && $this->settings['forum']['global']['reputation']['activated']) {
            $user = $obj->getCreatedBy();
            $result = $this->helper->calculateReputation($user, $this->settings);

            $user->setReputation($result);
            $this->userRepository->update($user);
            $this->persistenceManager->persistAll();
        }

        return $status;
    }

    /**
     * @param $obj
     * @return bool
     */
    public function checkVotedStatus($obj): bool
    {
        $result = false;
        if (count($obj) > 0) {
            foreach ($obj as $item) {
                if ($item->getUid() === $this->userId) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * @param object $object
     * @param string $type
     * @return array
     */
    public function getVoteResults(object $object, string $type): array
    {
        $results = [];
        $count = 0;
        $message = '';

        if ((int)$this->settings['forum']['global']['vote'][$type]['upVote'] && (int)$this->settings['forum']['global']['vote'][$type]['downVote']) {
            $upVoted = count($object->getUpvotedBy());
            $downVoted = count($object->getDownvotedBy());
            $count = $upVoted - $downVoted;
        } elseif ((int)$this->settings['forum']['global']['vote'][$type]['upVote'] && (int)$this->settings['forum']['global']['vote'][$type]['downVote'] === 0) {
            $count = count($object->getUpvotedBy());
        } elseif ((int)$this->settings['forum']['global']['vote'][$type]['upVote'] === 0 && (int)$this->settings['forum']['global']['vote'][$type]['downVote']) {
            $count = count($object->getDownvotedBy());
        } else {
            $message = "Invalid configuration. Although you have selected 'vote' as your reputation system and you defined that the post should be included in it, you have not defined the settings for upvote and downvote.";
        }
        $results = [
            'count' => $count,
            'message' => $message
        ];

        return $results;
    }
}