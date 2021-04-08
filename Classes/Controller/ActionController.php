<?php


namespace Karavas\AkForum\Controller;

use Karavas\AkForum\Domain\Model\FrontendUser;
use Karavas\AkForum\Domain\Repository\FrontendUserRepository;
use Karavas\AkForum\Helper\Helper;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * Class ActionController
 * @package Karavas\AkForum\Controller
 */
class ActionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * it is used to evaluate multiple things
     *
     * @var int
     */
    protected $userId = 0;

    /**
     * it is used to evaluate multiple things
     *
     * @var bool
     */
    protected $isLoggedIn = false;

    /**
     * save the frontend user object so it can be used to evaluate multiple things
     * or save the relation between the frontend user and other models such as post, reply
     *
     * @var FrontendUser
     *
     */
    protected $userInfo;

    /**
     * set to retrieve the user repository
     *
     * @var FrontendUserRepository
     *
     */
    protected $userRepository;

    /**
     * Gets the site configuration in order to redirect to the start page in case no error PID is defined
     *
     * @var SiteFinder
     *
     */
    protected $siteFinder;

    /**
     * Used in order to redirect to posts, themas or error PIDs in case something is manipulated
     *
     * @var UriBuilder
     *
     */
    protected $uriBuilder;

    /**
     * Gets the start url for the breadcrumbs
     *
     * @var string
     */
    protected $forumUrlDetail = '';

    /**
     *
     * Gets the start url for the breadcrumbs
     *
     * @var string
     */
    protected $topicUrlList = '';

    /**
     *
     * @var Helper
     */
    protected $helper;

    /**
     * @var array
     */
    protected $pluginParameters = [];

    /**
     * ActionController constructor.
     * @param Helper $helper
     * @param FrontendUserRepository $userRepository
     * @param SiteFinder $siteFinder
     * @param UriBuilder $uriBuilder
     * @throws AspectNotFoundException
     */
    public function __construct(
        Helper $helper,
        FrontendUserRepository $userRepository,
        SiteFinder $siteFinder,
        UriBuilder $uriBuilder
    )
    {
        $this->helper = $helper;
        $this->userRepository = $userRepository;
        $this->siteFinder = $siteFinder;
        $this->uriBuilder = $uriBuilder;
        $this->userId = $this->helper->getLoggedInfo()['userUId'];
        $this->isLoggedIn = $this->helper->getLoggedInfo()['isLoggedIn'];
    }

    /**
     * General initialization of actions and classes
     * @throws AspectNotFoundException
     * @throws InvalidActionNameException
     */
    public function initializeAction(): void
    {
        $this->resolvePluginParameters();
        parent::initializeAction();
        $this->forumUrlDetail = $this->settings['forum']['global']['forum']['startingPageDetail'];
        $this->topicUrlList = $this->settings['forum']['global']['topic']['startingPageList'];
        $this->userInfo = $this->helper->getLoggedInfo()['userInfo'];
        $this->settings = $this->helper->loadPluginSettings();
    }

    /**
     * Evaluates if the post or the reply that is about to be saved, comes from the same person that created it in the
     * first place
     *
     * @param int|$user
     * @param null $object $object
     * @param null|string $objectName
     * @param null|string $controllerName
     * @param null|string $actionName
     * @param null|string $extensionName
     * @param null|integer $pid
     * @throws StopActionException
     */
    public function evaluateUser(
        int $user,
        $object = null,
        $objectName = null,
        $controllerName = null,
        $actionName = null,
        $extensionName = null,
        $pid = null
    ): void
    {
        if ($this->isLoggedIn) {
            if ($this->userId !== $user) {
                $this->redirect($actionName, $controllerName, $extensionName, [$objectName => $object], $pid);
            }
        } else {
            $this->redirect($actionName, $controllerName, $extensionName, [$objectName => $object], $pid);
        }
    }

    /**
     * Evaluates if the user has manipulated the form, If yes then it redirects to the error page. In case no error page
     * is defined, then it redirects to the first site configuration's pid
     *
     * @param $hmac
     * @param $id
     * @throws StopActionException
     */
    public function evaluateHmac($hmac, $id): void
    {
        $pid = $this->settings['forum']['global']['errors']['errorPid'];
        if ($hmac && $id) {
            $objectHmac = GeneralUtility::hmac((int)$id, $this->settings['forum']['global']['encryptionPhrase']);
            if ($hmac !== $objectHmac) {
                $this->redirectToErrorDefinedPage($pid);
            }
        } else {
            $this->redirectToErrorDefinedPage($pid);
        }
    }

    /**
     *
     * redirects the user to the specified pid. If nothing has been defined, then redirects the user to the first site
     * configuration page
     *
     * @param null $pid
     * @throws StopActionException
     */
    public function redirectToErrorDefinedPage($pid = null): void
    {
        if ($pid) {
            $uri = $this->uriBuilder->reset()->setTargetPageUid($pid)->build();
            $this->redirectToUri($uri);
        } else {
            $request = $GLOBALS['TYPO3_REQUEST'];
            $site = $request->getAttribute('site');
            $uri = $site->getRouter()->generateUri($site->getRootPageId());
            $this->redirectToUri($uri);
        }
    }

    /**
     * redirects the user to the specified pid. If nothing has been defined, then redirects the user to the first site
     * configuration page
     *
     * @param $parentHmac
     * @param $childHmac
     * @param $parentId
     * @param $childUid
     * @throws StopActionException
     */
    public function evaluateParentChildHmac($parentHmac, $childHmac, $parentId, $childUid): void
    {
        if ($parentHmac && $childHmac) {
            $parentHmacGen = GeneralUtility::hmac((int)$parentId, $this->settings['forum']['global']['encryptionPhrase']);
            $childHmacGen = GeneralUtility::hmac((int)$childUid, $this->settings['forum']['global']['encryptionPhrase']);
            if ($parentHmac !== $parentHmacGen || $childHmac !== $childHmacGen) {
                $this->redirectToErrorDefinedPage();
            }
        } else {
            $this->redirectToErrorDefinedPage();
        }
    }

    /**
     * @throws InvalidActionNameException
     */
    public function resolvePluginParameters()
    {
        if (!empty(GeneralUtility::_GET()) && empty($this->request->getArguments())){
            foreach (GeneralUtility::_GET() as $key => $pluginParams) {
                if (strpos($key, 'tx_akforum') !== false) {
                    $this->request->setArguments($pluginParams);
                    if ($this->request->getArguments()['action']) {
                        $this->request->setControllerActionName($this->request->getArguments()['action']);
                        $this->request->setPluginName('');
                    }
                    break;
                }
            }
        }
    }

}