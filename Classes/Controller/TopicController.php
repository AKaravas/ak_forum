<?php
namespace Karavas\AkForum\Controller;

use Karavas\AkForum\Domain\Model\Topic;
use Karavas\AkForum\Domain\Repository\ForumRepository;
use Karavas\AkForum\Domain\Repository\FrontendUserRepository;
use Karavas\AkForum\Domain\Repository\TopicRepository;
use Karavas\AkForum\Helper\Helper;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
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
 * TopicController
 */
class TopicController extends ActionController
{

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
     * TopicController constructor.
     * @param Helper $helper
     * @param FrontendUserRepository $userRepository
     * @param SiteFinder $siteFinder
     * @param UriBuilder $uriBuilder
     * @throws AspectNotFoundException
     * @param ForumRepository $forumRepository
     * @param TopicRepository $topicRepository
     */
    public function __construct(
        Helper $helper,
        FrontendUserRepository $userRepository,
        SiteFinder $siteFinder,
        UriBuilder $uriBuilder,
        ForumRepository $forumRepository,
        TopicRepository $topicRepository
    )
    {
        parent::__construct($helper, $userRepository, $siteFinder, $uriBuilder);
        $this->forumRepository = $forumRepository;
        $this->topicRepository = $topicRepository;
    }

    /**
     * action list
     * 
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $topics = $this->topicRepository->findAll();
        $breadcrumbItems = [
            LocalizationUtility::translate('action_topic_list', 'AkForum') => $this->topicUrlList
        ];
        $this->view->assignMultiple([
            'breadcrumb' => $breadcrumbItems,
            'topics' => $topics
        ]);

        return $this->htmlResponse();
    }

    /**
     * action show
     * 
     * @param Topic $topic
     * @return ResponseInterface
     */
    public function showAction(Topic $topic): ResponseInterface
    {
        $forum = $this->forumRepository->findByUid($topic->getForum());
        $uri = $this->uriBuilder
            ->reset()
            ->setTargetPageUid($this->settings['forum']['global']['topic']['showPid'])
            ->uriFor('show', ['topic' => $topic->getUid()], 'Topic');

        if ($this->topicUrlList)
        {
            $breadcrumbItems = [
                LocalizationUtility::translate('action_topic_list', 'AkForum') => $this->topicUrlList,
                $topic->getName() => $uri
            ];
        }
        else {
            $breadcrumbItems = [
                $forum->getForumTitle() => $this->forumUrlDetail,
                $topic->getName() => $uri
            ];
        }
        $this->view->assignMultiple([
            'topic'=> $topic,
            'breadcrumb'=> $breadcrumbItems
        ]);

        return $this->htmlResponse();
    }
}
