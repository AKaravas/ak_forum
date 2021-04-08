<?php
namespace Karavas\AkForum\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Aristeidis Karavas <aristeidis.karavas@gmail.com>
 */
class PostTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Karavas\AkForum\Domain\Model\Post
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Karavas\AkForum\Domain\Model\Post();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSlugReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSlug()
        );
    }

    /**
     * @test
     */
    public function setSlugForStringSetsSlug()
    {
        $this->subject->setSlug('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'slug',
            $this->subject
        );
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
    public function getViewsReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getViews()
        );
    }

    /**
     * @test
     */
    public function setViewsForIntSetsViews()
    {
        $this->subject->setViews(12);

        self::assertAttributeEquals(
            12,
            'views',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getReplyRelReturnsInitialValueForReply()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getReplyRel()
        );
    }

    /**
     * @test
     */
    public function setReplyRelForObjectStorageContainingReplySetsReplyRel()
    {
        $replyRel = new \Karavas\AkForum\Domain\Model\Reply();
        $objectStorageHoldingExactlyOneReplyRel = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneReplyRel->attach($replyRel);
        $this->subject->setReplyRel($objectStorageHoldingExactlyOneReplyRel);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneReplyRel,
            'replyRel',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addReplyRelToObjectStorageHoldingReplyRel()
    {
        $replyRel = new \Karavas\AkForum\Domain\Model\Reply();
        $replyRelObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $replyRelObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($replyRel));
        $this->inject($this->subject, 'replyRel', $replyRelObjectStorageMock);

        $this->subject->addReplyRel($replyRel);
    }

    /**
     * @test
     */
    public function removeReplyRelFromObjectStorageHoldingReplyRel()
    {
        $replyRel = new \Karavas\AkForum\Domain\Model\Reply();
        $replyRelObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $replyRelObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($replyRel));
        $this->inject($this->subject, 'replyRel', $replyRelObjectStorageMock);

        $this->subject->removeReplyRel($replyRel);
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
    public function getFollowersReturnsInitialValueForFrontendUser()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getFollowers()
        );
    }

    /**
     * @test
     */
    public function setFollowersForObjectStorageContainingFrontendUserSetsFollowers()
    {
        $follower = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $objectStorageHoldingExactlyOneFollowers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneFollowers->attach($follower);
        $this->subject->setFollowers($objectStorageHoldingExactlyOneFollowers);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneFollowers,
            'followers',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addFollowerToObjectStorageHoldingFollowers()
    {
        $follower = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $followersObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $followersObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($follower));
        $this->inject($this->subject, 'followers', $followersObjectStorageMock);

        $this->subject->addFollower($follower);
    }

    /**
     * @test
     */
    public function removeFollowerFromObjectStorageHoldingFollowers()
    {
        $follower = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $followersObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $followersObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($follower));
        $this->inject($this->subject, 'followers', $followersObjectStorageMock);

        $this->subject->removeFollower($follower);
    }

    /**
     * @test
     */
    public function getCreatedByReturnsInitialValueForFrontendUser()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getCreatedBy()
        );
    }

    /**
     * @test
     */
    public function setCreatedByForObjectStorageContainingFrontendUserSetsCreatedBy()
    {
        $createdBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $objectStorageHoldingExactlyOneCreatedBy = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneCreatedBy->attach($createdBy);
        $this->subject->setCreatedBy($objectStorageHoldingExactlyOneCreatedBy);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneCreatedBy,
            'createdBy',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addCreatedByToObjectStorageHoldingCreatedBy()
    {
        $createdBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $createdByObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $createdByObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($createdBy));
        $this->inject($this->subject, 'createdBy', $createdByObjectStorageMock);

        $this->subject->addCreatedBy($createdBy);
    }

    /**
     * @test
     */
    public function removeCreatedByFromObjectStorageHoldingCreatedBy()
    {
        $createdBy = new \Karavas\AkForum\Domain\Model\FrontendUser();
        $createdByObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $createdByObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($createdBy));
        $this->inject($this->subject, 'createdBy', $createdByObjectStorageMock);

        $this->subject->removeCreatedBy($createdBy);
    }
}
