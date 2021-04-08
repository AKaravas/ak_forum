<?php


namespace Karavas\AkForum\Middlewares\FrontEnd;


use Karavas\AkForum\Ajax\AjaxRequests;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Http\JsonResponse;

class AjaxMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (count($request->getQueryParams()) > 0  && array_key_exists('extension', $request->getQueryParams())){
            if ($request->getQueryParams()['extension'] === 'forum') {
                $ajaxRequests = GeneralUtility::makeInstance(AjaxRequests::class);
                $action = $request->getQueryParams()['action'];
                $results = $ajaxRequests->$action($request->getQueryParams());
                return new JsonResponse($results);
            }
        }

        return $handler->handle($request);
    }
}