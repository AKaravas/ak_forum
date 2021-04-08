<?php


namespace Karavas\AkForum\Middlewares\FrontEnd;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\ErrorController;

class UserLoggedInMiddleware implements MiddlewareInterface
{

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ConfigurationManager
     */
    protected $configurationManager;

    /**
     * UserLoggedInMiddleware constructor.
     * @param Context $context
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(
        Context $context,
        ConfigurationManager $configurationManager
    )
    {
        $this->context = $context;
        $this->configurationManager = $configurationManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws AspectNotFoundException
     * @throws InvalidConfigurationTypeException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $globalSettings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        if (!$globalSettings['config.']['loginPage'])
        {
            return GeneralUtility::makeInstance(ErrorController::class)
                ->unavailableAction(
                    $request,
                    "You haven't defined a login page. Please make sure that the config.loginPage exists"
                );
        }
        $isLoggedIn = $this->context->getPropertyFromAspect('frontend.user', 'isLoggedIn');
        $routing = $request->getAttribute('routing');
        if (($routing->getPageId() !== (int)$globalSettings['config.']['loginPage']) && $isLoggedIn === false) {
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $linkConf = [
                'parameter' => $globalSettings['config.']['loginPage'],
                'forceAbsoluteUrl' => 1
            ];
            $url = $cObj->typolink_URL($linkConf);
            return new RedirectResponse($url, 302);
        }


        return $handler->handle($request);
    }
}