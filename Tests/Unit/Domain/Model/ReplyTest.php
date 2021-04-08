<?php
namespace Karavas\AkForum\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Aristeidis Karavas <aristeidis.karavas@gmail.com>
 */
class ReplyTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Karavas\AkForum\Domain\Model\Reply
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Karavas\AkForum\Domain\Model\Reply();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getBodyReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBody()
        );
    }

    /**
     * @test
     */
    public function setBodyForStringSetsBody()
    {
        $this->subject->setBody('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'body',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getLikedByReturnsInitialValueForFrontendUser()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getLikedBy()
        );
    }

    /**
     * @test
     */
    public function setLikedByForObjectStorageContainingFrontendUserSetsLikedBy()
    {
        $likedBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $objectStorageHoldingExactlyOneLikedBy = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneLikedBy->attach($likedBy);
        $this->subject->setLikedBy($objectStorageHoldingExactlyOneLikedBy);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneLikedBy,
            'likedBy',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addLikedByToObjectStorageHoldingLikedBy()
    {
        $likedBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $likedByObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $likedByObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($likedBy));
        $this->inject($this->subject, 'likedBy', $likedByObjectStorageMock);

        $this->subject->addLikedBy($likedBy);
    }

    /**
     * @test
     */
    public function removeLikedByFromObjectStorageHoldingLikedBy()
    {
        $likedBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $likedByObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $likedByObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($likedBy));
        $this->inject($this->subject, 'likedBy', $likedByObjectStorageMock);

        $this->subject->removeLikedBy($likedBy);
    }

    /**
     * @test
     */
    public function getDislikedByReturnsInitialValueForFrontendUser()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getDislikedBy()
        );
    }

    /**
     * @test
     */
    public function setDislikedByForObjectStorageContainingFrontendUserSetsDislikedBy()
    {
        $dislikedBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $objectStorageHoldingExactlyOneDislikedBy = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneDislikedBy->attach($dislikedBy);
        $this->subject->setDislikedBy($objectStorageHoldingExactlyOneDislikedBy);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneDislikedBy,
            'dislikedBy',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addDislikedByToObjectStorageHoldingDislikedBy()
    {
        $dislikedBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $dislikedByObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $dislikedByObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($dislikedBy));
        $this->inject($this->subject, 'dislikedBy', $dislikedByObjectStorageMock);

        $this->subject->addDislikedBy($dislikedBy);
    }

    /**
     * @test
     */
    public function removeDislikedByFromObjectStorageHoldingDislikedBy()
    {
        $dislikedBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $dislikedByObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $dislikedByObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($dislikedBy));
        $this->inject($this->subject, 'dislikedBy', $dislikedByObjectStorageMock);

        $this->subject->removeDislikedBy($dislikedBy);
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

    /**
     * @test
     */
    public function getUserReturnsInitialValueForFrontendUser()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getUser()
        );
    }

    /**
     * @test
     */
    public function setUserForObjectStorageContainingFrontendUserSetsUser()
    {
        $user = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $objectStorageHoldingExactlyOneUser = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneUser->attach($user);
        $this->subject->setUser($objectStorageHoldingExactlyOneUser);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneUser,
            'user',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addUserToObjectStorageHoldingUser()
    {
        $user = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $userObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $userObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($user));
        $this->inject($this->subject, 'user', $userObjectStorageMock);

        $this->subject->addUser($user);
    }

    /**
     * @test
     */
    public function removeUserFromObjectStorageHoldingUser()
    {
        $user = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $userObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $userObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($user));
        $this->inject($this->subject, 'user', $userObjectStorageMock);

        $this->subject->removeUser($user);
    }
}
