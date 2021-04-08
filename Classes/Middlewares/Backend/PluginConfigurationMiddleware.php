<?php

namespace Karavas\AkForum\Middlewares\Backend;

use Karavas\AkForum\Helper\Helper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PluginConfigurationMiddleware implements MiddlewareInterface
{
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * RemovePluginsMiddleware constructor.
     * @param Helper $helper
     */
    public function __construct(
        Helper $helper
    ) {
        $this->helper = $helper;
    }
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $pluginSettings = $this->helper->loadPluginSettings();
        if (empty($pluginSettings['forum']['global']['defaultForumUId'])) {
            $message = GeneralUtility::makeInstance(FlashMessage::class,
                'The default Forum has not been set in the settings. In order for the forum to work you need to specify a default forum UID',
                'AK Forum',
                FlashMessage::WARNING,
                true
            );
            $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
            $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
            $messageQueue->addMessage($message);
        }


        return $handler->handle($request);
    }
}