<?php
declare(strict_types=1);

namespace Karavas\AkForum\Domain\Model;


use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***
 *
 * This file is part of the "Just a forum" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Aristeidis Karavas <aristeidis.karavas@gmail.com>, Karavas
 *
 ***/
/**
 * Topic
 */
class Topic extends AbstractEntity
{

    /**
     * name
     * 
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $name = '';

    /**
     * slug
     * 
     * @var string
     */
    protected $slug = '';

    /**
     * themasRel
     * 
     * @var ObjectStorage<Thema>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $themasRel;

    /**
     * description
     * 
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $hmac = '';

    /**
     * forum
     *
     * @var int
     */
    protected $forum = 0;

    /**
     * @var string
     */
    protected $topicIcon = '';

    /**
     * images
     *
     * @var ObjectStorage<FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $topicImage;

    /**
     * __construct
     */
    public function __construct()
    {

        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     * 
     * @return void
     */
    protected function initStorageObjects(): void
    {
        $this->themasRel = new ObjectStorage();
        $this->topicImage = new ObjectStorage();
    }

    /**
     * Returns the name
     * 
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the topicIcon
     *
     * @return string $topicIcon
     */
    public function getTopicIcon(): string
    {
        return $this->topicIcon;
    }

    /**
     * Sets the topicIcon
     *
     * @param string $topicIcon
     * @return void
     */
    public function setTopicIcon(string $topicIcon): void
    {
        $this->topicIcon = $topicIcon;
    }

    /**
     * Returns the slug
     * 
     * @return string $slug
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Sets the slug
     *
     * @param string $slug
     * @return void
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Adds a Thema
     * 
     * @param Thema $themasRel
     * @return void
     */
    public function addThemasRel(Thema $themasRel): void
    {
        $this->themasRel->attach($themasRel);
    }

    /**
     * Removes a Thema
     * 
     * @param Thema $themasRelToRemove The Thema to be removed
     * @return void
     */
    public function removeThemasRel(Thema $themasRelToRemove): void
    {
        $this->themasRel->detach($themasRelToRemove);
    }

    /**
     * Returns the themasRel
     * 
     * @return ObjectStorage<Thema> $themasRel
     */
    public function getThemasRel()
    {
        return $this->themasRel;
    }

    /**
     * Sets the themasRel
     * 
     * @param ObjectStorage<Thema> $themasRel
     * @return void
     */
    public function setThemasRel(ObjectStorage $themasRel): void
    {
        $this->themasRel = $themasRel;
    }

    /**
     * Returns the description
     * 
     * @return string $description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Returns the hmac
     *
     * @return string $hmac
     */
    public function getHmac(): string
    {
        return $this->hmac;
    }

    /**
     * Sets the hmac
     *
     * @param string $hmac
     * @return void
     */
    public function setHmac(string $hmac): void
    {
        $this->hmac = $hmac;
    }

    /**
     * Returns the forum
     *
     * @return int
     */
    public function getForum(): int
    {
        return $this->forum;
    }

    /**
     * Adds a FileReference
     *
     * @param FileReference $topicImage
     * @return void
     */
    public function addTopicImage(FileReference $topicImage): void
    {
        $this->topicImage->attach($topicImage);
    }

    /**
     * Removes a FileReference
     *
     * @param FileReference $topicImageToRemove The FileReference to be removed
     * @return void
     */
    public function removeTopicImage(FileReference $topicImageToRemove): void
    {
        $this->topicImage->detach($topicImageToRemove);
    }

    /**
     * Returns the topicImage
     *
     * @return ObjectStorage<FileReference> $topicImage
     */
    public function getTopicImage()
    {
        return $this->topicImage;
    }

    /**
     * Sets the topicImage
     *
     * @param ObjectStorage<FileReference> $topicImage
     * @return void
     */
    public function setTopicImage(ObjectStorage $topicImage): void
    {
        $this->topicImage = $topicImage;
    }
}
