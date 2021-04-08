<?php
namespace Karavas\AkForum\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Aristeidis Karavas <aristeidis.karavas@gmail.com>
 */
class ThemaControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Karavas\AkForum\Controller\ThemaController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\Karavas\AkForum\Controller\ThemaController::class)
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
    public function listActionFetchesAllThemasFromRepositoryAndAssignsThemToView()
    {

        $allThemas = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $themaRepository = $this->getMockBuilder(\Karavas\AkForum\Domain\Repository\ThemaRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $themaRepository->expects(self::once())->method('findAll')->will(self::returnValue($allThemas));
        $this->inject($this->subject, 'themaRepository', $themaRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('themas', $allThemas);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenThemaToView()
    {
        $thema = new \Karavas\AkForum\Domain\Model\Thema();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('thema', $thema);

        $this->subject->showAction($thema);
    }
}
