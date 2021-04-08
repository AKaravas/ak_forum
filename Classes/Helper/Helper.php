<?php


namespace Karavas\AkForum\Helper;

use DateTime;
use Karavas\AkForum\Domain\Model\Activity;
use Karavas\AkForum\Domain\Model\FrontendUser;
use Karavas\AkForum\Domain\Model\Post;
use Karavas\AkForum\Domain\Model\Reactionrel;
use Karavas\AkForum\Domain\Model\Reply;
use Karavas\AkForum\Domain\Model\Thema;
use Karavas\AkForum\Domain\Repository\ActivityRepository;
use Karavas\AkForum\Domain\Repository\BackendUserRepository;
use Karavas\AkForum\Domain\Repository\FrontendUserRepository;
use Karavas\AkForum\Domain\Repository\PostRepository;
use Karavas\AkForum\Domain\Repository\ReplyRepository;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Karavas\AkForum\Domain\Repository\AwardRepository;

/**
 * Class Helper
 * @package Karavas\AkForum\Helper
 */
class Helper
{
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
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /***
     * @var ReplyRepository
     */
    protected $replyRepository;

    /***
     * @var PostRepository
     */
    protected $postRepository;

    /***
     * @var FrontendUserRepository
     */
    protected $userRepository;

    /***
     * @var BackendUserRepository
     */
    protected $backendUserRepository;

    /***
     * @var Context
     */
    protected $context;

    /**
     * Helper constructor.
     * @param PersistenceManager $persistenceManager
     * @param ReplyRepository $replyRepository
     * @param PostRepository $postRepository
     * @param FrontendUserRepository $frontendUserRepository
     * @param BackendUserRepository $backendUserRepository
     * @param ActivityRepository $activityRepository
     * @param AwardRepository $awardRepository
     * @param Context $context,
     */
    public function __construct(
        PersistenceManager $persistenceManager,
        ReplyRepository $replyRepository,
        PostRepository $postRepository,
        FrontendUserRepository $frontendUserRepository,
        BackendUserRepository $backendUserRepository,
        ActivityRepository $activityRepository,
        AwardRepository $awardRepository,
        Context $context
    ) {
        $this->persistenceManager = $persistenceManager;
        $this->replyRepository = $replyRepository;
        $this->postRepository = $postRepository;
        $this->userRepository = $frontendUserRepository;
        $this->backendUserRepository = $backendUserRepository;
        $this->activityRepository = $activityRepository;
        $this->awardRepository = $awardRepository;
        $this->context = $context;
    }

    /**
     * @return array
     * @throws AspectNotFoundException
     */
    public function getLoggedInfo(): array
    {
        $userId = $this->context->getPropertyFromAspect('frontend.user', 'id');
        return [
            'userUId' => $userId,
            'isLoggedIn' => $this->context->getPropertyFromAspect('frontend.user', 'isLoggedIn'),
            'userInfo' => $this->userRepository->findByUid((int)$userId)
        ];
    }


    /**
     * @return array
     **/
    public function loadTypoScript(): array
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $extbaseFrameworkConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

        return $extbaseFrameworkConfiguration['plugin.']['tx_akforum.'];
    }

    /**
     * @return array
     */
    public function loadPluginSettings(): array
    {
        return GeneralUtility::removeDotsFromTS($this->loadTypoScript()['settings.']);
    }

    /**
     * @param $user
     * @param $settings
     * @return int
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function calculateReputation($user, $settings): int
    {
        $repSum = 0;
        $repSystem = $settings['forum']['global']['system'];
        $userPosts = $this->postRepository->findByUser($user->getUid());
        $userReplies = $this->replyRepository->findByUser($user->getUid());

        if ($repSystem) {
            if ($repSystem === 'reaction') {
                if ($settings['forum']['global']['reputation']['reaction']['post']
                    || $settings['forum']['global']['reputation']['all']) {
                    foreach ($userPosts as $post) {
                        foreach ($post->getReactionRel() as $postSingleReaction) {
                            $repSum += ($postSingleReaction->getReaction()->getReputation());
                        }
                    }
                }
                if ($settings['forum']['global']['reputation']['reaction']['reply']
                    || $settings['forum']['global']['reputation']['all']) {
                    foreach ($userReplies as $reply) {
                        foreach ($reply->getReactionRel() as $replySingleReaction) {
                            $repSum += ($replySingleReaction->getReaction()->getReputation());
                        }
                    }
                }
            } elseif ($repSystem === 'vote') {
                $postResults = $replyResults = 0;
                if ((int)$settings['forum']['global']['reputation']['all']
                    || (int)$settings['forum']['global']['reputation']['vote']['post']['activated']) {
                    $postResults = $this->getVoteResults($userPosts, 'post', $settings);
                }
                if ((int)$settings['forum']['global']['reputation']['all']
                    || (int)$settings['forum']['global']['reputation']['vote']['reply']['activated']) {
                    $replyResults = $this->getVoteResults($userReplies, 'reply', $settings);
                }
                $repSum += ($postResults) + ($replyResults);
            }
        }
        if ((int)$settings['forum']['global']['reputation']['posts']['activated']
            || $settings['forum']['global']['reputation']['all']) {
            $postsPoints = (int)$settings['forum']['global']['reputation']['posts']['points'];
            $countedPosts = count($userPosts);
            $postSum = $countedPosts * $postsPoints;
            $repSum += $postSum;
        }
        if ((int)$settings['forum']['global']['reputation']['replies']['activated']
            || $settings['forum']['global']['reputation']['all']) {
            $repliesPoints = (int)$settings['forum']['global']['reputation']['replies']['points'];
            $countedReplies = count($userReplies);
            $repliesSum = $countedReplies * $repliesPoints;
            $repSum += $repliesSum;
        }
        if ((int)$settings['forum']['global']['awards']['all']
            || ((int)$settings['forum']['global']['awards']['all'] !== 1
                && (int)$settings['forum']['global']['awards']['reputation'])) {
            $postsAward = $this->awardRepository->findAllByAwardId(999990);
            $replies = count($this->replyRepository->findByUser($user->getUid()));
            $this->manageAwards($postsAward, $replies, $user, 999990, 'reputation', $settings);
            $this->persistenceManager->persistAll();
        }
        if ((int)$settings['forum']['global']['reputation']['all']
            || ((int)$settings['forum']['global']['reputation']['all'] !== 1
                && (int)$settings['forum']['global']['reputation']['awards'])) {
            $newUserInstance = $this->userRepository->findByUid($user->getUid());
            $awards = $newUserInstance->getAwards();
            if(count($awards) > 0) {
                foreach ($awards as $award) {
                    $repSum += $award->getReputation();
                }
            }
        }

        return $repSum;
    }

    /**
     * @param object $objects
     * @param string $type
     * @param array $settings
     * @return int
     */
    public function getVoteResults(object $objects, string $type, array $settings): int
    {
        $countUpVoted = $countDownVoted = $calculateUpVotes = $calculateDownVotes = 0;
        $upVotePoints = (int)$settings['forum']['global']['reputation']['vote'][$type]['upVote']['points'];
        $downVotePoints = (int)$settings['forum']['global']['reputation']['vote'][$type]['downVote']['points'];
        foreach ($objects as $object) {
            if ((int)$settings['forum']['global']['reputation']['vote'][$type]['upVote']['activated']
                || (int)$settings['forum']['global']['reputation']['all']) {
                if (count($object->getUpvotedBy()) > 0) {
                    $countUpVoted += count($object->getUpvotedBy());
                }
            }
            if ((int)$settings['forum']['global']['reputation']['vote'][$type]['downVote']['activated']
                || (int)$settings['forum']['global']['reputation']['all']) {
                if (count($object->getDownvotedBy()) > 0) {
                    $countDownVoted += count($object->getDownvotedBy());
                }
            }
        }
        $calculateUpVotes = $countUpVoted * $upVotePoints;
        $calculateDownVotes = $countDownVoted * $downVotePoints;

        return $calculateUpVotes + ($calculateDownVotes);
    }

    /**
     * @param $awards
     * @param int $numberToCompare
     * @param $user
     * @param string $type
     * @param int $reasonId
     * @param array|null $settings
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function manageAwards($awards, int $numberToCompare, $user,  int $reasonId, string $type, array $settings): void
    {
        if (count($awards) > 0) {
            $awarded = [];
            foreach ($awards as $award) {
                if ($numberToCompare >= $award->getAmount()) {
                    $awarded[] = $award;
                }
            }
            if ($awarded) {
                $awardToGet = end($awarded);
                if (count($user->getAwards()) > 0) {
                    $currentAward = null;
                    foreach ($user->getAwards() as $userAward) {
                        if ($userAward->getReason() === $reasonId) {
                            $currentAward = $userAward;
                        }
                    }
                    if ($currentAward) {
                        if ($awardToGet) {
                            if ($awardToGet->getAmount() < $currentAward->getAmount()) {
                                if ((int)$settings['forum']['global']['awards']['userLosesAward']['all']
                                    || ((int)$settings['forum']['global']['awards']['userLosesAward']['all'] !== 1
                                        && (int)$settings['forum']['global']['awards']['userLosesAward'][$type])) {
                                    foreach ($user->getAwards() as $userAward) {
                                        if ($userAward->getReason() === $reasonId) {
                                            $user->removeAwards($userAward);
                                            $user->addAwards($awardToGet);
                                            $this->userRepository->update($user);
                                        }
                                    }
                                }
                            }
                            else {
                                $user->removeAwards($currentAward);
                                $user->addAwards($awardToGet);
                                $this->userRepository->update($user);
                            }
                        } else {
                            $user->removeAwards($currentAward);
                            $this->userRepository->update($user);
                        }
                    } else {
                        $user->addAwards($awardToGet);
                        $this->userRepository->update($user);
                    }
                }else {
                    $user->addAwards($awardToGet);
                    $this->userRepository->update($user);
                }
            } elseif (count($user->getAwards()) > 0) {
                $currentAward = null;
                foreach ($user->getAwards() as $userAward) {
                    if ($userAward->getReason() === $reasonId) {
                        $currentAward = $userAward;
                    }
                }
                if ($currentAward) {
                    if ((int)$settings['forum']['global']['awards']['userLosesAward']['all']
                        || ((int)$settings['forum']['global']['awards']['userLosesAward']['all'] !== 1
                            && (int)$settings['forum']['global']['awards']['userLosesAward'][$type])) {
                        $user->removeAwards($currentAward);
                        $this->userRepository->update($user);
                    }
                }
            }
            $this->persistenceManager->persistAll();
        }
    }

    /**
     * @param string $action
     * @param int $persistenceId
     * @param string $title
     * @param string $template
     * @param FrontendUser $user
     * @param Activity|null $activity
     * @param Post|null $post
     * @param Reply|null $reply
     * @param Thema|null $thema
     * @param Reactionrel|null $reaction
     * @param FrontendUser|null $foreignUser
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function manageActivity(
        string $action,
        int $persistenceId,
        string $title,
        string $template,
        FrontendUser $user,
        Activity $activity = null,
        Post $post = null,
        Reply $reply = null,
        Thema $thema = null,
        Reactionrel $reaction = null,
        FrontendUser $foreignUser = null
    ): void {
        if (($action === 'removed' || $action === 'no-vote') && $activity) {
            $this->activityRepository->remove($activity);
            $this->persistenceManager->persistAll();
        } elseif ($activity) {
            if ($reaction) {
                $activity->setReaction($reaction);
            }
            if ($action === 'upVoted' || $action === 'downVoted')
            {
                $activity->setVoteAction($action);
            }
            $now = new DateTime('now');
            $activity->setTstamp($now->getTimestamp());
            $this->activityRepository->update($activity);
            $this->persistenceManager->persistAll();
        }
        else {
            $newActivity = GeneralUtility::makeInstance(Activity::class);
            $newActivity->setPid($persistenceId);
            $newActivity->setTitle($title);
            $newActivity->setTemplate($template);
            $newActivity->setUser($user);

            if ($post) {
                $newActivity->setPost($post);
            }
            if ($reply) {
                $newActivity->setReply($reply);
            }
            if ($thema) {
                $newActivity->setThema($thema);
            }
            if ($foreignUser) {
                $newActivity->setForeignUser($foreignUser);
            }
            if ($reaction) {
                $newActivity->setReaction($reaction);
            }
            if ($action === 'upVoted' || $action === 'downVoted') {
                $newActivity->setVoteAction($action);
            }
            $this->activityRepository->add($newActivity);
            $this->persistenceManager->persistAll();
        }
    }

    /**
     * @param $sender
     * @param $subject
     * @param $template
     * @param null $arguments
     * @param null $settings
     * @param null $additionalInfo
     */
    public function sendMailToGroup(
        $sender,
        $subject,
        $template,
        $arguments = null,
        $settings = null,
        $additionalInfo = null
    ): void {
        $backendGroupId = (int)$settings['forum']['global']['errors']['mail']['toGroup']['groupId'];
        $users = $this->backendUserRepository->findByGroupId($backendGroupId);
        if (count($users) > 0) {
            foreach ($users as $userToNotify) {
                if ($userToNotify->getEmail()) {
                    $this->sendMail($sender, $userToNotify->getEmail(), $userToNotify->getUsername(), $subject,
                        $template, $userToNotify, $arguments, $additionalInfo);
                }
            }
        }
    }

    /**
     * @param $sender
     * @param $recipientMail
     * @param $recipientUsername
     * @param $subject
     * @param $template
     * @param null $user
     * @param null $arguments
     * @param null $additionalInfo
     */
    public function sendMail(
        $sender,
        $recipientMail,
        $recipientUsername,
        $subject,
        $template,
        $user = null,
        $arguments = null,
        $additionalInfo = null
    ): void {
        $emailView = GeneralUtility::makeInstance(StandaloneView::class);
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $conf = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $emailView->setTemplateRootPaths($conf['plugin.']['tx_akforum_akforum.']['view.']['templateRootPaths.']);
        $emailView->setLayoutRootPaths($conf['plugin.']['tx_akforum_akforum.']['view.']['layoutRootPaths.']);
        $emailView->setPartialRootPaths($conf['plugin.']['tx_akforum_akforum.']['view.']['layoutRootPaths.']);
        $emailView->setTemplate('Email/' . $template . '.html');
        if ($arguments) {
            $emailView->assign('arguments', $arguments);
        }
        if ($user) {
            $emailView->assign('user', $user);
        }
        if ($additionalInfo) {
            $emailView->assign('additionalInfo', $additionalInfo);
        }

        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new Address($sender['email'], $sender['username']));
        $mail->to(new Address($recipientMail, $recipientUsername));
        $mail->subject($subject);
        $emailBody = $emailView->render();
        $mail->html($emailBody, 'text/html');

        $mail->send();
    }

    /**
    * @param $maxLinks
    * @param $currentPageNumber
    * @param $pagination
    * @param $separator
    * @return array
    */
    public function createPaginationResults($maxLinks, $currentPageNumber, $pagination, $separator): array
    {
        if ($pagination->getLastPageNumber() > 10) {
            if ($maxLinks && is_numeric($maxLinks) && $maxLinks >= 2) {
                //$pages = range(0, $startEndOffset);
                $pages = [];
                $pages[] = 1;
                if ($currentPageNumber === 1) {
                    for ($i = 2; $i <= $maxLinks; $i++) {
                        $pages[] = $i;
                    }
                } elseif ($currentPageNumber < $maxLinks && $maxLinks < $pagination->getLastPageNumber()) {
                    for ($i = 2; $i <= $maxLinks; $i++) {
                        $pages[] = $i;
                    }
                } elseif ($currentPageNumber === $pagination->getLastPageNumber()) {
                    for ($i = $pagination->getLastPageNumber(); $i > ($pagination->getLastPageNumber() - $maxLinks) + 1; $i--) {
                        $pages[] = $i - 1;
                    }
                    sort($pages);
                } elseif ($currentPageNumber > $maxLinks && ($maxLinks + $currentPageNumber - 1) > $pagination->getLastPageNumber()) {
                    for ($i = $currentPageNumber + ($pagination->getLastPageNumber() - $currentPageNumber) - 1; $i > ($currentPageNumber - $maxLinks) + ($pagination->getLastPageNumber() - $currentPageNumber); $i--) {
                        $pages[] = $i;
                    }
                    sort($pages);
                } else {
                    for (
                        $i = max(2, $currentPageNumber - floor(($maxLinks - 1) / 2));
                        $i <= min($currentPageNumber + ceil(($maxLinks - 1) / 2),
                            $pagination->getLastPageNumber() - 1); $i++
                    ) {
                        $pages[] = $i;
                    }
                }
                $pages[] = $pagination->getLastPageNumber();
            } else {
                $pages = range(1, $pagination->getLastPageNumber());
            }

            foreach ($pages as $key => $page) {
                $newPage = $separator;
                if (($key === 0) && $pages[1] !== $page + 1) {
                    array_splice($pages, 1, 0, $newPage);
                }
                $itemBeforeLast = count($pages) - 2;
                if (is_numeric($pages[$itemBeforeLast]) && ($key === $itemBeforeLast) && $pages[$itemBeforeLast + 1] !== $pages[$itemBeforeLast] + 1) {
                    array_splice($pages, $itemBeforeLast + 1, 0, $newPage);
                }
            }
        } else {
            $pages = range(1, $pagination->getLastPageNumber());
        }

        return [
            'pages' => $pages
        ];
    }
}