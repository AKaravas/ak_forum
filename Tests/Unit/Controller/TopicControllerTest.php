<?php
namespace Karavas\AkForum\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Aristeidis Karavas <aristeidis.karavas@gmail.com>
 */
class TopicControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Karavas\AkForum\Controller\TopicController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\Karavas\AkForum\Controller\TopicController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllTopicsFromRepositoryAndAssignsThemToView()
    {

        $allTopics = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $topicRepository = $this->getMockBuilder(\Karavas\AkForum\Domain\Repository\TopicRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $topicRepository->expects(self::once())->method('findAll')->will(self::returnValue($allTopics));
        $this->inject($this->subject, 'topicRepository', $topicRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('topics', $allTopics);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenTopicToView()
    {
        $topic = new \Karavas\AkForum\Domain\Model\Topic();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('topic', $topic);

        $this->subject->showAction($topic);
    }
}
