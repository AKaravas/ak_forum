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
 * Thema
 */
class Thema extends AbstractEntity
{

    /**
     * name
     * 
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $name = '';

    /**
     * description
     * 
     * @var string
     */
    protected $description = '';

    /**
     * slug
     * 
     * @var string
     */
    protected $slug = '';

    /**
     * topic
     *
     * @var int
     */
    protected $topic = 0;

    /**
     * @var string
     */
    protected $themaIcon = '';

    /**
     * images
     *
     * @var ObjectStorage<FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $themaImage;

    /**
     * postsRel
     * 
     * @var ObjectStorage<Post>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $postsRel;

    /**
     * followers
     * 
     * @var ObjectStorage<FrontendUser>
     */
    protected $followers;

    /**
     * @var string
     */
    protected $hmac = '';

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
        $this->postsRel = new ObjectStorage();
        $this->followers = new ObjectStorage();
        $this->themaImage = new ObjectStorage();
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
     * Returns the themaIcon
     *
     * @return string $themaIcon
     */
    public function getThemaIcon(): string
    {
        return $this->themaIcon;
    }

    /**
     * Sets the themaIcon
     *
     * @param string $themaIcon
     * @return void
     */
    public function setThemaIcon(string $themaIcon): void
    {
        $this->themaIcon = $themaIcon;
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
     * Adds a Post
     * 
     * @param Post $postsRel
     * @return void
     */
    public function addPostsRel(Post $postsRel): void
    {
        $this->postsRel->attach($postsRel);
    }

    /**
     * Removes a Post
     * 
     * @param Post $postsRelToRemove The Post to be removed
     * @return void
     */
    public function removePostsRel(Post $postsRelToRemove): void
    {
        $this->postsRel->detach($postsRelToRemove);
    }

    /**
     * Returns the postsRel
     * 
     * @return ObjectStorage<Post> $postsRel
     */
    public function getPostsRel(): ?ObjectStorage
    {
        return $this->postsRel;
    }

    /**
     * Sets the postsRel
     * 
     * @param ObjectStorage<Post> $postsRel
     * @return void
     */
    public function setPostsRel(ObjectStorage $postsRel): void
    {
        $this->postsRel = $postsRel;
    }

    /**
     * Adds a FrontendUser
     * 
     * @param FrontendUser $follower
     * @return void
     */
    public function addFollower(FrontendUser $follower): void
    {
        $this->followers->attach($follower);
    }

    /**
     * Removes a FrontendUser
     * 
     * @param FrontendUser $followerToRemove The FrontendUser to be removed
     * @return void
     */
    public function removeFollower(FrontendUser $followerToRemove): void
    {
        $this->followers->detach($followerToRemove);
    }

    /**
     * Returns the followers
     * 
     * @return ObjectStorage<FrontendUser> $followers
     */
    public function getFollowers(): ?ObjectStorage
    {
        return $this->followers;
    }

    /**
     * Sets the followers
     * 
     * @param ObjectStorage<FrontendUser> $followers
     * @return void
     */
    public function setFollowers(ObjectStorage $followers): void
    {
        $this->followers = $followers;
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
     * Returns the topic
     *
     * @return int topic
     */
    public function getTopic(): int
    {
        return $this->topic;
    }

    /**
     * Adds a FileReference
     *
     * @param FileReference $themaImage
     * @return void
     */
    public function addThemaImage(FileReference $themaImage): void
    {
        $this->themaImage->attach($themaImage);
    }

    /**
     * Removes a FileReference
     *
     * @param FileReference $themaImageToRemove The FileReference to be removed
     * @return void
     */
    public function removeThemaImage(FileReference $themaImageToRemove): void
    {
        $this->themaImage->detach($themaImageToRemove);
    }

    /**
     * Returns the themaImage
     *
     * @return ObjectStorage<FileReference> $themaImage
     */
    public function getThemaImage(): ?ObjectStorage
    {
        return $this->themaImage;
    }

    /**
     * Sets the themaImage
     *
     * @param ObjectStorage<FileReference> $themaImage
     * @return void
     */
    public function setThemaImage(ObjectStorage $themaImage)
    {
        $this->themaImage = $themaImage;
    }
}
