<?php


namespace Karavas\AkForum\Controller;

use Karavas\AkForum\Domain\Model\Post;
use Karavas\AkForum\Domain\Model\Reply;
use Karavas\AkForum\Domain\Repository\ActivityRepository;
use Karavas\AkForum\Domain\Repository\FrontendUserRepository;
use Karavas\AkForum\Domain\Repository\PostRepository;
use Karavas\AkForum\Domain\Repository\ReplyRepository;
use Karavas\AkForum\Domain\Repository\ThemaRepository;
use Karavas\AkForum\Domain\Repository\AwardRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Karavas\AkForum\Helper\Helper;

/**
 * Class ReplyController
 * @package Karavas\AkForum\Controller
 */
class ReplyController extends ActionController
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
     * themaRepository
     *
     * @var ThemaRepository
     */
    protected $themaRepository;

    /**
     * replyRepository
     *
     * @var ReplyRepository
     */
    protected $replyRepository;
    /**
     * activityRepository
     *
     * @var ActivityRepository
     */
    protected $activityRepository;

    /**
     * PersistenceManager
     *
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * ReplyController constructor.
     * @param Helper $helper
     * @param FrontendUserRepository $userRepository
     * @param SiteFinder $siteFinder
     * @param UriBuilder $uriBuilder
     * @param PostRepository $postRepository
     * @param ThemaRepository $themaRepository
     * @param ReplyRepository $replyRepository
     * @param PersistenceManager $persistenceManager
     * @param AwardRepository $awardRepository
     * @param ActivityRepository $activityRepository
     * @throws AspectNotFoundException
     */
    public function __construct
    (
        Helper $helper,
        FrontendUserRepository $userRepository,
        SiteFinder $siteFinder,
        UriBuilder $uriBuilder,
        PostRepository $postRepository,
        ThemaRepository $themaRepository,
        ReplyRepository $replyRepository,
        PersistenceManager $persistenceManager,
        AwardRepository $awardRepository,
        ActivityRepository $activityRepository
    )
    {
        parent::__construct($helper, $userRepository, $siteFinder, $uriBuilder);
        $this->postRepository = $postRepository;
        $this->themaRepository = $themaRepository;
        $this->replyRepository = $replyRepository;
        $this->persistenceManager = $persistenceManager;
        $this->awardRepository = $awardRepository;
        $this->activityRepository = $activityRepository;
    }

    /**
     * @throws AspectNotFoundException
     * @throws StopActionException
     * @throws InvalidActionNameException
     */
    public function initializeAction(): void
    {
        parent::initializeAction();
        $args = $this->request->getArguments();
        switch ($this->request->getControllerActionName())
        {
            case 'new':
                $this->evaluateHmac($args['hmac'], $args['post']);
                break;
            case 'create':
                $this->evaluateHmac($args['hmac'], $args['post']['__identity']);
                break;
            case 'update':
                $this->evaluateParentChildHmac(
                    $args['post_hmac'],
                    $args['reply_hmac'],
                    $args['post']['__identity'],
                    $args['reply']['__identity']
                );
                break;
            case 'edit':
            case 'delete':
                $this->evaluateParentChildHmac(
                    $args['postHmac'],
                    $args['hmac'],
                    $args['post'],
                    $args['reply']
                );
                break;
        }
    }

    /**
     * new action
     * @throws StopActionException
     *
     * @return ResponseInterface
     */
    public function newAction(): ResponseInterface
    {
        $args = $this->request->getArguments();
        if ($args['newReply']) {
            return new ForwardResponse('create');
        }
        $post = $this->postRepository->findByUid($args['post']);
        $this->evaluateUser($this->userId, $post, 'post','Post', 'show', 'AkForum', (int)$this->settings['forum']['global']['post']['showPid']);
        $currentList = $GLOBALS['TSFE']->fe_user->getKey('ses', 'post-'.(int)$args['post']);
        if (count($currentList) > 0)
        {
            $replies = $this->replyRepository->findByUids($currentList);
            $results = '';
            foreach ($replies as $reply)
            {
                $results .= "<blockquote class='blockquote'><div class='citation'> <b>". LocalizationUtility::translate('blockquote_user_said', 'AkForum', [$reply->getCreatedBy()->getUserName()])."</b></div><div class='quoted_content'>".$reply->getBody()."</div></blockquote><p> </p>";
            }
            $this->view->assign('quoted', $results);
        }

        $this->view->assignMultiple([
            'post' => $post,
        ]);
        return $this->htmlResponse();
    }

    /**
     * @param Reply $newReply
     * @param Post $post
     * @throws StopActionException
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     *
     * @return void
     */
    public function createAction(Reply $newReply, Post $post): void
    {
        $persistenceId = $this->settings['forum']['global']['post']['persistenceId'];

        $newReply->setCreatedBy($this->userInfo);
        $this->replyRepository->add($newReply);
        $this->persistenceManager->persistAll();

        $hmac = GeneralUtility::hmac( $newReply->getUid(), $this->settings['forum']['global']['encryptionPhrase']);
        $newReply->setHmac($hmac);
        $post->addReplyRel($newReply);
        $this->postRepository->update($post);

        $currentList = $GLOBALS['TSFE']->fe_user->getKey('ses', 'post-' . $post->getUid());
        foreach($currentList as $key => $reply)
        {
            unset($currentList[$key]);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'post-'.$post->getUid(), $currentList);
            $GLOBALS['TSFE']->fe_user->storeSessionData();
        }

        if ((int)$this->settings['forum']['global']['reputation']['activated']
            && (int)$this->settings['forum']['global']['reputation']['replies']['activated']) {
            $user = $newReply->getCreatedBy();
            $result = $this->helper->calculateReputation($user, $this->settings);
            $user->setReputation($result);
            $this->userRepository->update($user);
            $this->persistenceManager->persistAll();
        }
        if ((int)$this->settings['forum']['global']['awards']['all']
            || ((int)$this->settings['forum']['global']['awards']['all'] !== 1
                && (int)$this->settings['forum']['global']['awards']['replies'])) {
            $postsAward = $this->awardRepository->findAllByAwardId(999992);
            $replies = count($this->replyRepository->findByUser($this->userId));
            $this->helper->manageAwards($postsAward, $replies, $this->userInfo, 999992, 'replies', $this->settings);
        }
        if ((int)$this->settings['forum']['global']['activity']['all']['activated']
            || ((int)$this->settings['forum']['global']['activity']['all']['activated'] === 0
                && (int)$this->settings['forum']['global']['activity']['createdReply'])) {
            $this->helper->manageActivity('', $persistenceId, 'Replied to post ' . $post->getTitle(), 'Replied', $this->userInfo, null, $post, $newReply);
        }

        $this->redirect('show', 'Post', 'AkForum', ['post'=> $post], $this->settings['forum']['global']['post']['showPid']);
    }

    /**
     * @param Reply $reply
     * @param Post $post
     * @throws StopActionException
     * @return ResponseInterface
     */
    public function editAction(Reply $reply, Post $post): ResponseInterface
    {
        $args = $this->request->getArguments();
        if ($args['reply']['__identity']) {
            return new ForwardResponse('update');
        }
        if ($reply instanceof Reply && $post instanceof Post)
        {
            if ($this->userId !== $reply->getCreatedBy()->getUid())
            {
                $this->redirect('show', 'Post', 'AkForum', ['post'=> $post], $this->settings['forum']['global']['post']['showPid']);
            }
            $this->view->assign('reply', $reply);
            $this->view->assign('post', $post);
        }
        return $this->htmlResponse();
    }
    /**
     * @param Reply $reply
     * @param Post $post
     * @throws StopActionException
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     *
     * @return void
     */
    public function updateAction(Reply $reply, Post $post): void
    {
        $persistenceId = $this->settings['forum']['global']['post']['persistenceId'];
        $this->evaluateUser($reply->getCreatedBy()->getUid(), $post, 'post','Post', 'show', 'AkForum', (int)$this->settings['forum']['global']['post']['showPid']);
        if ($this->settings['forum']['global']['reply']['edit']['timesToEdit'])
        {
            if ($reply->getTimesEdited() >= $this->settings['forum']['global']['reply']['edit']['timesToEdit'])
            {
                $this->redirect('show', 'Post', 'AkForum', ['post'=> $post], $this->settings['forum']['global']['post']['showPid']);
            }
            $reply->setTimesEdited((int)$reply->getTimesEdited() +1);
        }
        $this->replyRepository->update($reply);
        if ((int)$this->settings['forum']['global']['activity']['all']['activated']
            || ((int)$this->settings['forum']['global']['activity']['all']['activated'] === 0
                && (int)$this->settings['forum']['global']['activity']['updatedReply'])) {
            $activity = $this->activityRepository->findByReplyAndTemplate($this->userId, $post->getUid(), 'Replied');
            $this->helper->manageActivity('', $persistenceId, 'Updated the reply on the post ' . $post->getTitle(),
                'UpdatedReply', $this->userInfo, $activity, $post, $reply);
        }
        $this->redirect('show', 'Post', 'AkForum', ['post'=> $post], $this->settings['forum']['global']['post']['showPid']);
    }

    /**
     * @param Reply $reply
     * @param Post $post
     * @return void
     * @throws IllegalObjectTypeException
     * @throws StopActionException
     * @throws UnknownObjectException
     */
    public function deleteAction(Reply $reply, Post $post): void
    {
        if ($reply instanceof Reply && $post instanceof Post) {
            $this->evaluateUser($reply->getCreatedBy()->getUid(), $post, 'post','Post', 'show', 'AkForum', (int)$this->settings['forum']['global']['post']['showPid']);
            $this->replyRepository->remove($reply);
            $this->persistenceManager->persistAll();
            if ((int)$this->settings['forum']['global']['awards']['all']
                || ((int)$this->settings['forum']['global']['awards']['all'] !== 1
                    && (int)$this->settings['forum']['global']['awards']['replies'])) {
                $postsAward = $this->awardRepository->findAllByAwardId(999992);
                $replies = count($this->replyRepository->findByUser($this->userId));
                $this->helper->manageAwards($postsAward, $replies, $this->userInfo, 999992, 'replies', $this->settings);
            }
            if ((int)$this->settings['forum']['global']['reputation']['activated']
                && (int)$this->settings['forum']['global']['reputation']['replies']['activated']) {
                $result = $this->helper->calculateReputation($this->userInfo, $this->settings);

                $this->userInfo->setReputation($result);
                $this->userRepository->update($this->userInfo);
                $this->persistenceManager->persistAll();
            }
            $this->redirect('show', 'Post', 'AkForum', ['post'=> $post], $this->settings['forum']['global']['post']['showPid']);
        }
        else {
            $this->redirect('show', 'Post', 'AkForum', ['post'=> $post], $this->settings['forum']['global']['post']['showPid']);
        }
    }
}