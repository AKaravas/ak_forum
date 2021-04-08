<?php
namespace Karavas\AkForum\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Aristeidis Karavas <aristeidis.karavas@gmail.com>
 */
class TopicTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Karavas\AkForum\Domain\Model\Topic
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Karavas\AkForum\Domain\Model\Topic();
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
    public function getThemasRelReturnsInitialValueForThema()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getThemasRel()
        );
    }

    /**
     * @test
     */
    public function setThemasRelForObjectStorageContainingThemaSetsThemasRel()
    {
        $themasRel = new \Karavas\AkForum\Domain\Model\Thema();
        $objectStorageHoldingExactlyOneThemasRel = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneThemasRel->attach($themasRel);
        $this->subject->setThemasRel($objectStorageHoldingExactlyOneThemasRel);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneThemasRel,
            'themasRel',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addThemasRelToObjectStorageHoldingThemasRel()
    {
        $themasRel = new \Karavas\AkForum\Domain\Model\Thema();
        $themasRelObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $themasRelObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($themasRel));
        $this->inject($this->subject, 'themasRel', $themasRelObjectStorageMock);

        $this->subject->addThemasRel($themasRel);
    }

    /**
     * @test
     */
    public function removeThemasRelFromObjectStorageHoldingThemasRel()
    {
        $themasRel = new \Karavas\AkForum\Domain\Model\Thema();
        $themasRelObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $themasRelObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($themasRel));
        $this->inject($this->subject, 'themasRel', $themasRelObjectStorageMock);

        $this->subject->removeThemasRel($themasRel);
    }
}
