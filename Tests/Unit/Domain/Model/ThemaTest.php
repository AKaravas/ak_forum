<?php
namespace Karavas\AkForum\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Aristeidis Karavas <aristeidis.karavas@gmail.com>
 */
class ThemaTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Karavas\AkForum\Domain\Model\Thema
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Karavas\AkForum\Domain\Model\Thema();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'name',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription()
    {
        $this->subject->setDescription('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'description',
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
    public function getPostsRelReturnsInitialValueForPost()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getPostsRel()
        );
    }

    /**
     * @test
     */
    public function setPostsRelForObjectStorageContainingPostSetsPostsRel()
    {
        $postsRel = new \Karavas\AkForum\Domain\Model\Post();
        $objectStorageHoldingExactlyOnePostsRel = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOnePostsRel->attach($postsRel);
        $this->subject->setPostsRel($objectStorageHoldingExactlyOnePostsRel);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOnePostsRel,
            'postsRel',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addPostsRelToObjectStorageHoldingPostsRel()
    {
        $postsRel = new \Karavas\AkForum\Domain\Model\Post();
        $postsRelObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $postsRelObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($postsRel));
        $this->inject($this->subject, 'postsRel', $postsRelObjectStorageMock);

        $this->subject->addPostsRel($postsRel);
    }

    /**
     * @test
     */
    public function removePostsRelFromObjectStorageHoldingPostsRel()
    {
        $postsRel = new \Karavas\AkForum\Domain\Model\Post();
        $postsRelObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $postsRelObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($postsRel));
        $this->inject($this->subject, 'postsRel', $postsRelObjectStorageMock);

        $this->subject->removePostsRel($postsRel);
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
}
