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
 * Award
 */
class Award extends AbstractEntity
{

    /**
     * name
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $name = '';

    /**
     * name
     *
     * @var string
     */
    protected $icon = '';

    /**
     * amount
     *
     * @var int
     */
    protected $amount= 0;

    /**
     * reputation
     *
     * @var int
     */
    protected $reputation= 0;

    /**
     * level
     *
     * @var Awardlevel
     *
     */
    protected $level;

    /**
     * reason
     *
     * @var int
     */
    protected $reason;

    /**
     * image
     *
     * @var ObjectStorage<FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $image;


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
        $this->image = new ObjectStorage();
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
     * Returns the icon
     *
     * @return string $icon
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Sets the icon
     *
     * @param string $icon
     * @return void
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * Returns the amount
     *
     * @return int $amount
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Sets the amount
     *
     * @param int $amount
     * @return void
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * Returns the level
     *
     * @return Awardlevel $level
     */
    public function getLevel(): ?Awardlevel
    {
        return $this->level;
    }

    /**
     * sets the level
     *
     * @param Awardlevel  $awardlevel
     */
    public function setLevel(Awardlevel $awardlevel): void
    {
       $this->level = $awardlevel;
    }

    /**
     * Returns the reason
     *
     * @return int $reason
     */
    public function getReason(): ?int
    {
        return $this->reason;
    }

    /**
     * sets the reason
     *
     * @param int $reason
     */
    public function setReason(int $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * Adds a FileReference
     *
     * @param FileReference $image
     * @return void
     */
    public function addImage(FileReference $image): void
    {
        $this->image->attach($image);
    }

    /**
     * Removes a FileReference
     *
     * @param FileReference $imageToRemove The FileReference to be removed
     * @return void
     */
    public function removeImage(FileReference $imageToRemove): void
    {
        $this->image->detach($imageToRemove);
    }

    /**
     * Returns the image
     *
     * @return ObjectStorage<FileReference> $image
     */
    public function getImage(): ?ObjectStorage
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param ObjectStorage<FileReference> $image
     * @return void
     */
    public function setImage(ObjectStorage $image): void
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getReputation(): int
    {
        return $this->reputation;
    }

    /**
     * @param int $reputation
     */
    public function setReputation(int $reputation): void
    {
        $this->reputation = $reputation;
    }

}
