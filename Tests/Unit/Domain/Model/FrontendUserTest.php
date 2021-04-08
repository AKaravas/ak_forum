<?php
namespace Karavas\AkForum\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Aristeidis Karavas <aristeidis.karavas@gmail.com>
 */
class FrontendUserTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Karavas\AkForum\Domain\Model\FrontendUser
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Karavas\AkForum\Domain\Model\FrontendUser();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getReputationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getReputation()
        );
    }

    /**
     * @test
     */
    public function setReputationForStringSetsReputation()
    {
        $this->subject->setReputation('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'reputation',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getReportedByReturnsInitialValueForFrontendUser()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getReportedBy()
        );
    }

    /**
     * @test
     */
    public function setReportedByForObjectStorageContainingFrontendUserSetsReportedBy()
    {
        $reportedBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $objectStorageHoldingExactlyOneReportedBy = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneReportedBy->attach($reportedBy);
        $this->subject->setReportedBy($objectStorageHoldingExactlyOneReportedBy);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneReportedBy,
            'reportedBy',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addReportedByToObjectStorageHoldingReportedBy()
    {
        $reportedBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $reportedByObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $reportedByObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($reportedBy));
        $this->inject($this->subject, 'reportedBy', $reportedByObjectStorageMock);

        $this->subject->addReportedBy($reportedBy);
    }

    /**
     * @test
     */
    public function removeReportedByFromObjectStorageHoldingReportedBy()
    {
        $reportedBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $reportedByObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $reportedByObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($reportedBy));
        $this->inject($this->subject, 'reportedBy', $reportedByObjectStorageMock);

        $this->subject->removeReportedBy($reportedBy);
    }
}
