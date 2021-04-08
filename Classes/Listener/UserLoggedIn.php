<?php


namespace Karavas\AkForum\Listener;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Event\Mvc\BeforeActionCallEvent;
use TYPO3\CMS\Core\Http\Request;

class UserLoggedIn
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Request
     */
    protected $request;


    public function __construct(
        Context $context,
        Request $request
    )
    {
        $this->context = $context;
        $this->request = $request;
    }

    public function checkUser(): void
    {
        $request = $this->request;
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($request);
        die();
        $isLoggedIn = $this->context->getPropertyFromAspect('frontend.user', 'isLoggedIn');
        if (!$isLoggedIn) {

        }
    }
}