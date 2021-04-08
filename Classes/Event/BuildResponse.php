<?php


namespace Karavas\AkForum\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;

class BuildResponse
{
    /**
     * @var SiteInterface
     */
    private $site;
    /**
     * @var ServerRequestInterface
     */
    private $request;
    /**
     * @var ResponseInterface|null
     */
    private $response;

    public function __construct(SiteInterface $site, ServerRequestInterface $request)
    {
        $this->site = $site;
        $this->request = $request;;
    }

    public function getSite(): SiteInterface
    {
        return $this->site;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->response);
        die();
    }
}