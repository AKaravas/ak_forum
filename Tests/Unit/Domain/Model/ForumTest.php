<?php
namespace Karavas\AkForum\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Aristeidis Karavas <aristeidis.karavas@gmail.com>
 */
class ForumTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Karavas\AkForum\Domain\Model\Forum
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Karavas\AkForum\Domain\Model\Forum();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getForumTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getForumTitle()
        );
    }

    /**
     * @test
     */
    public function setForumTitleForStringSetsForumTitle()
    {
        $this->subject->setForumTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'forumTitle',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTopicsRelReturnsInitialValueForTopic()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getTopicsRel()
        );
    }

    /**
     * @test
     */
    public function setTopicsRelForObjectStorageContainingTopicSetsTopicsRel()
    {
        $topicsRel = new \Karavas\AkForum\Domain\Model\Topic();
        $objectStorageHoldingExactlyOneTopicsRel = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneTopicsRel->attach($topicsRel);
        $this->subject->setTopicsRel($objectStorageHoldingExactlyOneTopicsRel);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneTopicsRel,
            'topicsRel',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addTopicsRelToObjectStorageHoldingTopicsRel()
    {
        $topicsRel = new \Karavas\AkForum\Domain\Model\Topic();
        $topicsRelObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $topicsRelObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($topicsRel));
        $this->inject($this->subject, 'topicsRel', $topicsRelObjectStorageMock);

        $this->subject->addTopicsRel($topicsRel);
    }

    /**
     * @test
     */
    public function removeTopicsRelFromObjectStorageHoldingTopicsRel()
    {
        $topicsRel = new \Karavas\AkForum\Domain\Model\Topic();
        $topicsRelObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $topicsRelObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($topicsRel));
        $this->inject($this->subject, 'topicsRel', $topicsRelObjectStorageMock);

        $this->subject->removeTopicsRel($topicsRel);
    }
}
