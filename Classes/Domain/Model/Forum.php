<?php
declare(strict_types=1);

namespace Karavas\AkForum\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
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
 * Forum
 */
class Forum extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * forumTitle
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $forumTitle = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * topicsRel
     *
     * @var ObjectStorage<Topic>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $topicsRel;

    /**
     * @var string
     */
    protected $hmac = '';

    /**
     * @var string
     */
    protected $forumIcon = '';

    /**
     * images
     *
     * @var ObjectStorage<FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $forumImage;

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
        $this->topicsRel = new ObjectStorage();
        $this->forumImage = new ObjectStorage();
    }

    /**
     * Returns the forumTitle
     *
     * @return string $forumTitle
     */
    public function getForumTitle(): string
    {
        return $this->forumTitle;
    }

    /**
     * Sets the forumTitle
     *
     * @param string $forumTitle
     * @return void
     */
    public function setForumTitle(string $forumTitle): void
    {
        $this->forumTitle = $forumTitle;
    }

    /**
     * Returns the forumIcon
     *
     * @return string $forumIcon
     */
    public function getForumIcon(): string
    {
        return $this->forumIcon;
    }

    /**
     * Sets the forumIcon
     *
     * @param string $forumIcon
     * @return void
     */
    public function setForumIcon(string $forumIcon): void
    {
        $this->forumIcon = $forumIcon;
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
     * Adds a Topic
     *
     * @param Topic $topicsRel
     * @return void
     */
    public function addTopicsRel(Topic $topicsRel): void
    {
        $this->topicsRel->attach($topicsRel);
    }

    /**
     * Removes a Topic
     *
     * @param Topic $topicsRelToRemove The Topic to be removed
     * @return void
     */
    public function removeTopicsRel(Topic $topicsRelToRemove): void
    {
        $this->topicsRel->detach($topicsRelToRemove);
    }

    /**
     * Returns the topicsRel
     *
     * @return ObjectStorage<Topic> $topicsRel
     */
    public function getTopicsRel(): ?ObjectStorage
    {
        return $this->topicsRel;
    }

    /**
     * Sets the topicsRel
     *
     * @param ObjectStorage<Topic> $topicsRel
     * @return void
     */
    public function setTopicsRel(ObjectStorage $topicsRel): void
    {
        $this->topicsRel = $topicsRel;
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
     * Adds a FileReference
     *
     * @param FileReference $forumImage
     * @return void
     */
    public function addForumImage(FileReference $forumImage): void
    {
        $this->forumImage->attach($forumImage);
    }

    /**
     * Removes a FileReference
     *
     * @param FileReference $forumImageToRemove The FileReference to be removed
     * @return void
     */
    public function removeForumImage(FileReference $forumImageToRemove): void
    {
        $this->forumImage->detach($forumImageToRemove);
    }

    /**
     * Returns the forumImage
     *
     * @return ObjectStorage<FileReference> $forumImage
     */
    public function getForumImage(): ?ObjectStorage
    {
        return $this->forumImage;
    }

    /**
     * Sets the forumImage
     *
     * @param ObjectStorage<FileReference> $forumImage
     * @return void
     */
    public function setForumImage(ObjectStorage $forumImage): void
    {
        $this->forumImage = $forumImage;
    }
}
