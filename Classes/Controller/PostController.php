<?php

namespace Karavas\AkForum\Controller;

use Karavas\AkForum\Domain\Model\Post;
use Karavas\AkForum\Domain\Model\Thema;
use Karavas\AkForum\Domain\Repository\ActivityRepository;
use Karavas\AkForum\Domain\Repository\AwardRepository;
use Karavas\AkForum\Domain\Repository\ForumRepository;
use Karavas\AkForum\Domain\Repository\FrontendUserRepository;
use Karavas\AkForum\Domain\Repository\PostRepository;
use Karavas\AkForum\Domain\Repository\ReactionrelRepository;
use Karavas\AkForum\Domain\Repository\ReactionRepository;
use Karavas\AkForum\Domain\Repository\ReplyRepository;
use Karavas\AkForum\Domain\Repository\ThemaRepository;
use Karavas\AkForum\Domain\Repository\TopicRepository;
use Karavas\AkForum\Helper\Helper;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
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
 * PostController
 */
class PostController extends ActionController
{
    /***
     * @var AwardRepository
     */
    protected $awardRepository;

    /**
     * postRepository
     *
     * @var PostRepository
     *
     */
    protected $postRepository;

    /**
     * replyRepository
     *
     * @var ReplyRepository
     *
     */
    protected $replyRepository;

    /**
     * themaRepository
     *
     * @var ThemaRepository
     */
    protected $themaRepository;

    /**
     * activityRepository
     *
     * @var ActivityRepository
     */
    protected $activityRepository;
    /**
     * themaRepository
     *
     * @var ReactionRepository
     */
    protected $reactionRepository;

    /**
     * PersistenceManager
     *
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var ReactionrelRepository
     */
    protected $reactionrelRepository;

    /**
     * @var TopicRepository
     */
    protected $topicRepository;

    /**
     * @var ForumRepository
     */
    protected $forumRepository;

    /**
     * ActionController constructor.
     *
     * @param Helper $helper
     * @param FrontendUserRepository $userRepository
     * @param SiteFinder $siteFinder
     * @param UriBuilder $uriBuilder
     * @param ThemaRepository $themaRepository
     * @param PostRepository $postRepository
     * @param PersistenceManager $persistenceManager
     * @param ReactionRepository $reactionRepository
     * @param ReactionrelRepository $reactionrelRepository
     * @param AwardRepository $awardRepository
     * @param ActivityRepository $activityRepository
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
        PostRepository $postRepository,
        PersistenceManager $persistenceManager,
        ReactionRepository $reactionRepository,
        ReactionrelRepository $reactionrelRepository,
        AwardRepository $awardRepository,
        ActivityRepository $activityRepository,
        TopicRepository $topicRepository,
        ForumRepository $forumRepository,
        ReplyRepository $replyRepository
    ) {
        parent::__construct($helper, $userRepository, $siteFinder, $uriBuilder);
        $this->themaRepository = $themaRepository;
        $this->postRepository = $postRepository;
        $this->persistenceManager = $persistenceManager;
        $this->reactionRepository = $reactionRepository;
        $this->reactionrelRepository = $reactionrelRepository;
        $this->awardRepository = $awardRepository;
        $this->activityRepository = $activityRepository;
        $this->topicRepository = $topicRepository;
        $this->forumRepository = $forumRepository;
        $this->replyRepository = $replyRepository;
    }

    /**
     * @throws AspectNotFoundException
     * @throws IllegalObjectTypeException
     * @throws InvalidActionNameException
     * @throws NoSuchArgumentException
     * @throws StopActionException
     * @throws UnknownObjectException
     */
    public function initializeAction(): void
    {
        parent::initializeAction();
        $args = $this->request->getArguments();
        switch ($this->request->getControllerActionName()) {
            case 'delete':
                $this->evaluateHmac($args['hmac'], $args['post']);
                break;
            case 'create':
            case 'new':
                $this->evaluateHmac($args['hmac'], $args['thema']['__identity']);
                break;
        }
        if (empty($this->request->getPluginName()) && $this->request->getControllerActionName()) {
            switch ($this->request->getControllerActionName()) {
                case 'delete':
                    $this->deleteWithoutView();
                    break;
            }
        }
    }

    /**
     * action list
     *
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $posts = $this->postRepository->findAll();
        $this->view->assign('posts', $posts);

        return $this->htmlResponse();
    }

    /**
     *
     * action show
     *
     * @param Post|null $post
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function showAction(Post $post = null): ResponseInterface
    {
        if ($post instanceof Post) {
            if ($this->isLoggedIn) {
                $userId = $this->userInfo->getUid();
                $ids = [];
                if (count($post->getViews()) === 0) {
                    $post->addView($this->userInfo);
                    $this->postRepository->update($post);
                    $this->persistenceManager->persistAll();
                } else {
                    foreach ($post->getViews() as $userRead) {
                        $ids[] = $userRead->getUid();
                    }
                    if ($userId !== null && !in_array($userId, $ids, true)) {
                        $post->addView($this->userInfo);
                        $this->postRepository->update($post);
                        $this->persistenceManager->persistAll();
                    }
                }
                $persistenceId = $this->settings['forum']['global']['post']['persistenceId'];
                if ((int)$this->settings['forum']['global']['activity']['all']
                    || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                        && (int)$this->settings['forum']['global']['activity']['visitedPost'])) {
                    $activity = $this->activityRepository->findByPostAndTemplate($this->userId, $post->getUid(),
                        'VisitedPost');
                    $this->helper->manageActivity('', $persistenceId, 'Visited the post ' . $post->getTitle(),
                        'VisitedPost', $this->userInfo, $activity, $post);
                }
            }
            $thema = $this->themaRepository->findByUid($post->getThema());
            $topic = $this->topicRepository->findByUid($thema->getTopic());
            $forum = $this->forumRepository->findByUid($topic->getForum());
            $topicUri = $this->uriBuilder
                ->reset()
                ->setTargetPageUid($this->settings['forum']['global']['topic']['showPid'])
                ->uriFor('show', ['topic' => $topic->getUid()], 'Topic');
            $themaUri = $this->uriBuilder
                ->reset()
                ->setTargetPageUid($this->settings['forum']['global']['thema']['showPid'])
                ->uriFor('show', ['thema' =>$thema->getUid()], 'Thema');
            $postUri = $this->uriBuilder
                ->reset()
                ->setTargetPageUid($this->settings['forum']['global']['post']['showPid'])
                ->uriFor('show', ['post' => $post->getUid()], 'Post');

            if ($this->topicUrlList) {
                $breadcrumbItems = [
                    LocalizationUtility::translate('action_topic_list', 'AkForum') => $this->topicUrlList,
                    $topic->getName() => $topicUri,
                    $thema->getName() => $themaUri,
                    $post->getTitle() => $postUri
                ];
            } else {
                $breadcrumbItems = [
                    $forum->getForumTitle() => $this->forumUrlDetail,
                    $topic->getName() => $topicUri,
                    $thema->getName() => $themaUri,
                    $post->getTitle() => $postUri
                ];
            }

            $reactionList = $this->reactionRepository->findAll();

            $postCountedItems = [];
            foreach ($reactionList as $reaction) {
                $postCountedItems[$reaction->getName()] = count($this->reactionrelRepository->findReactionsByNamePost($post,
                    $reaction->getName()));
            }
            $userChoice = '';
            foreach ($post->getReactionRel() as $userReacted) {
                if ($userReacted->getUser()->getUid() === $this->userId) {
                    $userChoice = $userReacted->getReaction()->getName();
                }
            }

            /*
             * Post Temporary variables
             */
            $post->setCountedItems($postCountedItems);
            $post->setUserReaction($userChoice);


            if ((int)$this->settings['forum']['global']['awards']['all']
                || ((int)$this->settings['forum']['global']['awards']['all'] !== 1
                    && (int)$this->settings['forum']['global']['awards']['postViews'])) {
                $postViewsAwards = $this->awardRepository->findAllByAwardId(999994);
                $posts = $this->postRepository->findByUser($post->getCreatedBy());
                $postViews = 0;
                foreach ($posts as $postToCheck) {
                    $postViews += count($postToCheck->getViews());
                }
                $user = $post->getCreatedBy();
                $this->helper->manageAwards($postViewsAwards, $postViews, $user, 999994,'postViews', $this->settings);
            }

            foreach ($post->getFollowers() as $follower) {
                if ($follower->getUid() === $this->userId) {
                    $this->view->assign('isFollowing', 1);
                }
            }
            if ((int)$this->request->getArguments()['currentPage']) {
                $currentPageNumber = (int)$this->request->getArguments()['currentPage'];
            } else {
                $currentPageNumber = 1;
            }
            $itemsPerPage = $this->settings['forum']['global']['reply']['paginate']['itemsPerPage'];
            $offset = $itemsPerPage * $currentPageNumber;
            $paginatedReplies = $this->replyRepository->findByPostId($post->getUid(), $itemsPerPage,$offset);

            $countReplies = 0;
            /*
             * Reply Temporary variables
             */
            foreach ($paginatedReplies as $reply) {
                $replyCountedItems = [];
                foreach ($reactionList as $reaction) {
                    $replyCountedItems[$reaction->getName()] = count($this->reactionrelRepository->findReactionsByNameReply($reply,
                        $reaction->getName()));
                }
                $userReplyChoice = '';
                foreach ($reply->getReactionRel() as $userReplyReacted) {
                    if ($userReplyReacted->getUser()->getUid() === $this->userId) {
                        $userReplyChoice = $userReplyReacted->getReaction()->getName();
                    }
                }
                $reply->setCountedItems($replyCountedItems);
                $reply->setUserReaction($userReplyChoice);
            }

            $paginator = new QueryResultPaginator($paginatedReplies, $currentPageNumber, $itemsPerPage);
            $pagination = new SimplePagination($paginator);
            $paginationResults = $this->helper->createPaginationResults($this->settings['forum']['global']['reply']['paginate']['maximumNumberOfLinks'], $currentPageNumber, $pagination, $this->settings['forum']['global']['reply']['paginate']['separator']);

            $this->view->assignMultiple([
                'userUid' => $this->userId,
                'post' => $post,
                'breadcrumb' => $breadcrumbItems,
                'reactionList' => $reactionList,
                'pagination_settings' => [
                    'paginator' => $paginator,
                    'pagination' => $pagination,
                    'pages' => $paginationResults['pages'],
                    'currentPage' => $paginator->getCurrentPageNumber(),
                    'separator' =>  $this->settings['forum']['global']['reply']['paginate']['separator'],
                    'paginationLinksParams' => [
                        'action' => 'show',
                        'controller' => 'Post'
                    ]
                ],

            ]);
        } else {
            $this->view->assign('message', LocalizationUtility::translate('post_not_found', 'AkForum'));
        }

        return $this->htmlResponse();

    }

    /**
     * action new
     *
     * @return ResponseInterface
     */
    public function newAction(): ResponseInterface
    {
        $args = $this->request->getArguments();
        if ($args['newPost']) {
            return new ForwardResponse('create');
        }
        $thema = $this->themaRepository->findByUid((int)$args['thema']);
        $this->view->assign('thema', $thema);

        return $this->htmlResponse();
    }

    /**
     * @param Post $newPost
     * @param Thema $thema
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     *
     * @throws StopActionException
     */
    public function createAction(Post $newPost, Thema $thema): ResponseInterface
    {
        $persistenceId = $this->settings['forum']['global']['post']['persistenceId'];
        if ($persistenceId) {
            $newPost->setPid($persistenceId);
        }

        $newPost->setCreatedBy($this->userInfo);
        $this->postRepository->add($newPost);
        $this->persistenceManager->persistAll();

        $hmac = GeneralUtility::hmac($newPost->getUid(), $this->settings['forum']['global']['encryptionPhrase']);
        $changedTitle = strtolower(str_replace(' ', '-', $newPost->getTitle()));
        $newPost->setSlug($newPost->getUid() . '-' . $changedTitle);
        $newPost->setHmac($hmac);
        $thema->addPostsRel($newPost);
        $this->themaRepository->update($thema);
        $this->persistenceManager->persistAll();

        if ((int)$this->settings['forum']['global']['reputation']['activated']
            && (int)$this->settings['forum']['global']['reputation']['posts']['activated']) {
            $result = $this->helper->calculateReputation($this->userInfo, $this->settings);
            $this->userInfo->setReputation($result);
            $this->userRepository->update($this->userInfo);
            $this->persistenceManager->persistAll();
        }
        if ((int)$this->settings['forum']['global']['awards']['all']
            || ((int)$this->settings['forum']['global']['awards']['all'] !== 1
            && (int)$this->settings['forum']['global']['awards']['posts'])) {
            $postsAward = $this->awardRepository->findAllByAwardId(999991);
            $posts = count($this->postRepository->findByUser($this->userId));
            $this->helper->manageAwards($postsAward, $posts, $this->userInfo,999991, 'posts' ,$this->settings);
        }
        if ((int)$this->settings['forum']['global']['activity']['all']
            || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                && (int)$this->settings['forum']['global']['activity']['createdPost'])) {
            $this->helper->manageActivity('', $persistenceId, 'Created the post ' . $newPost->getTitle(), 'Posted',
                $this->userInfo, null, $newPost);
        }

        $this->redirect('show', 'Post', 'AkForum', ['post' => $newPost],
            $this->settings['forum']['global']['post']['showPid']);

        return  $this->htmlResponse();
    }

    /**
     * @param Post|null $post
     * @return ResponseInterface
     * @throws StopActionException
     * @IgnoreValidation("post")
     */
    public function editAction(Post $post = null): ResponseInterface
    {
        $args = $this->request->getArguments();
        if ($args['post']['__identity']) {
            return new ForwardResponse('update');
        }
        if ($post instanceof Post) {
            if ($this->userId !== $post->getCreatedBy()->getUid()) {
                $this->redirect('show', 'Post', 'AkForum', ['post' => $post],
                    $this->settings['forum']['global']['post']['showPid']);
            }
            if (((int)$this->settings['forum']['global']['post']['edit']['editNoMatterWhat'] !== 1)
                && $this->settings['forum']['global']['post']['edit']['timesToEdit']
                && $post->getTimesEdited() >= $this->settings['forum']['global']['post']['edit']['timesToEdit']) {
                $this->redirect('show', 'Post', 'AkForum', ['post' => $post],
                    $this->settings['forum']['global']['post']['showPid']);
            }
            $this->view->assign('post', $post);
        } else {
            $this->view->assign('message', LocalizationUtility::translate('post_not_found', 'AkForum'));
        }

        return $this->htmlResponse();

    }

    /**
     * @param Post $post
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     * @throws StopActionException
     */
    public function updateAction(Post $post): ResponseInterface
    {
        $persistenceId = $this->settings['forum']['global']['post']['persistenceId'];
        if ($this->userId !== $post->getCreatedBy()->getUid()) {
            $this->redirect('show', 'Post', 'AkForum', ['post' => $post],
                $this->settings['forum']['global']['post']['showPid']);
        }
        if ((int)$this->settings['forum']['global']['post']['edit']['editNoMatterWhat'] === 0 && $this->settings['forum']['global']['post']['edit']['timesToEdit'] && $post->getTimesEdited() >= $this->settings['forum']['global']['post']['edit']['timesToEdit']) {
            $this->redirect('show', 'Post', 'AkForum', ['post' => $post],
                $this->settings['forum']['global']['post']['showPid']);
        }
        $post->setTimesEdited($post->getTimesEdited() + 1);
        $this->postRepository->update($post);
        if ((int)$this->settings['forum']['global']['activity']['all']
            || ((int)$this->settings['forum']['global']['activity']['all'] === 0
                && (int)$this->settings['forum']['global']['activity']['updatedPost'])) {
            $activity = $this->activityRepository->findByPostAndTemplate($this->userId, $post->getUid(), 'UpdatedPost');
            $this->helper->manageActivity('', $persistenceId, 'Updated the post ' . $post->getTitle(), 'UpdatedPost',
                $this->userInfo, $activity, $post);
        }
        $this->redirect('show', 'Post', 'AkForum', ['post' => $post],
            $this->settings['forum']['global']['post']['showPid']);

        return $this->htmlResponse();
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws NoSuchArgumentException
     * @throws StopActionException
     * @throws UnknownObjectException
     */
    public function deleteWithoutView()
    {
        $post = $this->postRepository->findByUid($this->request->getArgument('post'));
        if ($this->userId !== $post->getCreatedBy()->getUid()) {
            $this->redirect('show', 'Post', 'AkForum', ['post' => $post],
                $this->settings['forum']['global']['post']['showPid']);
        }
        $this->deletePost($post);
    }

    /**
     * @param Post $post
     * @return ResponseInterface|null
     * @throws IllegalObjectTypeException
     * @throws StopActionException
     * @throws UnknownObjectException
     */
    public function deleteAction(Post $post): ?ResponseInterface
    {
        if ($this->userId !== $post->getCreatedBy()->getUid()) {
            $this->redirect('show', 'Post', 'AkForum', ['post' => $post],
                $this->settings['forum']['global']['post']['showPid']);
        }
        $this->deletePost($post);

        return $this->htmlResponse();
    }

    /**
     * @param Post $post
     * @throws IllegalObjectTypeException
     * @throws StopActionException
     * @throws UnknownObjectException
     */
    public function deletePost(Post $post)
    {
        $thema = $post->getThema();
        $this->postRepository->remove($post);
        $this->persistenceManager->persistAll();

        if ((int)$this->settings['forum']['global']['awards']['all']
            || ((int)$this->settings['forum']['global']['awards']['all'] !== 1
                && (int)$this->settings['forum']['global']['awards']['posts'])) {
            $postsAward = $this->awardRepository->findAllByAwardId(999991);
            $posts = count($this->postRepository->findByUser($this->userId));
            $this->helper->manageAwards($postsAward, $posts, $this->userInfo, 999991, 'posts', $this->settings);
        }
        if ((int)$this->settings['forum']['global']['awards']['all']
            || ((int)$this->settings['forum']['global']['awards']['all'] !== 1
                && (int)$this->settings['forum']['global']['awards']['postViews'])) {
            $postsAward = $this->awardRepository->findAllByAwardId(999994);
            $posts = count($this->postRepository->findByUser($this->userId));
            $this->helper->manageAwards($postsAward, $posts, $this->userInfo, 999994, 'postViews', $this->settings);
        }
        if ((int)$this->settings['forum']['global']['reputation']['activated']
            && (int)$this->settings['forum']['global']['reputation']['posts']['activated']) {
            $result = $this->helper->calculateReputation($this->userInfo, $this->settings);

            $this->userInfo->setReputation($result);
            $this->userRepository->update($this->userInfo);
            $this->persistenceManager->persistAll();
        }
        if ($this->settings['forum']['global']['post']['delete']['redirectAfterDelete']) {
            $uri = $this->uriBuilder->reset()->setTargetPageUid($this->settings['forum']['global']['post']['delete']['redirectAfterDelete'])->build();
            if ($uri) {
                $this->redirectToUri($uri);
            } else {
                $this->redirect('show', 'Thema', 'AkForum', ['thema' => $thema],
                    $this->settings['forum']['global']['thema']['showPid']);
            }
        } else {
            $this->redirect('show', 'Thema', 'AkForum', ['thema' => $thema],
                $this->settings['forum']['global']['thema']['showPid']);
        }
    }
}
