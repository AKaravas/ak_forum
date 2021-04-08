<?php
declare(strict_types=1);

namespace Karavas\AkForum\Domain\Model;


use DateTime;
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
 * Post
 */
class Post extends AbstractEntity
{

    /**
     * title
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';

    /**
     * canEdit
     *
     * @var boolean
     */
    protected $canEdit = false;

    /**
     * canDelete
     *
     * @var boolean
     */
    protected $canDelete = false;

    /**
     * pinned
     *
     * @var boolean
     */
    protected $pinned = false;

    /**
     * featured
     *
     * @var boolean
     */
    protected $featured = false;

    /**
     * @var DateTime
     */
    protected $tstamp;

    /**
     * @var DateTime
     */
    protected $crdate;

    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';

    /**
     * body
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $body = '';

    /**
     * timesEdited
     *
     * @var int
     */
    protected $timesEdited = 0;

    /**
     * thema
     *
     * @var int
     *
     */
    protected $thema = 0;

    /**
     * @var string
     */
    protected $hmac = '';

    /**
     * replyRel
     *
     * @var ObjectStorage<Reply>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $replyRel;

    /**
     * reactionRel
     *
     * @var ObjectStorage<Reactionrel>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $reactionRel;

    /**
     * reportedBy
     *
     * @var ObjectStorage<FrontendUser>
     */
    protected $reportedBy;

    /**
     * upvotedBy
     *
     * @var ObjectStorage<FrontendUser>
     */
    protected $upvotedBy;

    /**
     * downvotedBy
     *
     * @var ObjectStorage<FrontendUser>
     */
    protected $downvotedBy;

    /**
     * followers
     *
     * @var ObjectStorage<FrontendUser>
     */
    protected $followers;

    /**
     * views
     *
     * @var ObjectStorage<FrontendUser>
     */
    protected $views;


    /**
     * createdBy
     *
     * @var FrontendUser
     */
    protected $createdBy;

    /**
     * @var array
     */
    protected $countedItems = [];


    /**
     * @var string
     */
    protected $userReaction = '';

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
        $this->replyRel = new ObjectStorage();
        $this->reportedBy = new ObjectStorage();
        $this->upvotedBy = new ObjectStorage();
        $this->downvotedBy = new ObjectStorage();
        $this->followers = new ObjectStorage();
        $this->views = new ObjectStorage();
        $this->reactionRel = new ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
     * Returns the body
     *
     * @return string $body
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Sets the body
     *
     * @param string $body
     * @return void
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }


    /**
     * Returns the timesEdited
     *
     * @return int $timesEdited
     */
    public function getTimesEdited(): int
    {
        return $this->timesEdited;
    }

    /**
     * Sets the timesEdited
     *
     * @param int $timesEdited
     * @return void
     */
    public function setTimesEdited(int $timesEdited): void
    {
        $this->timesEdited = $timesEdited;
    }

    /**
     * Adds a Reply
     *
     * @param Reply $replyRel
     * @return void
     */
    public function addReplyRel(Reply $replyRel): void
    {
        $this->replyRel->attach($replyRel);
    }

    /**
     * Removes a Reply
     *
     * @param Reply $replyRelToRemove The Reply to be removed
     * @return void
     */
    public function removeReplyRel(Reply $replyRelToRemove): void
    {
        $this->replyRel->detach($replyRelToRemove);
    }

    /**
     * Returns the replyRel
     *
     * @return ObjectStorage<Reply> $replyRel
     */
    public function getReplyRel(): ?ObjectStorage
    {
        return $this->replyRel;
    }

    /**
     * Sets the replyRel
     *
     * @param ObjectStorage<Reply> $replyRel
     * @return void
     */
    public function setReplyRel(ObjectStorage $replyRel): void
    {
        $this->replyRel = $replyRel;
    }

    /**
     * Adds a FrontendUser
     *
     * @param FrontendUser $reportedBy
     * @return void
     */
    public function addReportedBy(FrontendUser $reportedBy): void
    {
        $this->reportedBy->attach($reportedBy);
    }

    /**
     * Removes a FrontendUser
     *
     * @param FrontendUser $reportedByToRemove The FrontendUser to be removed
     * @return void
     */
    public function removeReportedBy(FrontendUser $reportedByToRemove): void
    {
        $this->reportedBy->detach($reportedByToRemove);
    }

    /**
     * Returns the reportedBy
     *
     * @return ObjectStorage<FrontendUser> $reportedBy
     */
    public function getReportedBy(): ?ObjectStorage
    {
        return $this->reportedBy;
    }

    /**
     * Sets the reportedBy
     *
     * @param ObjectStorage<FrontendUser> $reportedBy
     * @return void
     */
    public function setReportedBy(ObjectStorage $reportedBy): void
    {
        $this->reportedBy = $reportedBy;
    }

    /**
     * Adds a FrontendUser
     *
     * @param FrontendUser $upvotedBy
     * @return void
     */
    public function addUpvotedBy(FrontendUser $upvotedBy): void
    {
        $this->upvotedBy->attach($upvotedBy);
    }

    /**
     * Removes a FrontendUser
     *
     * @param FrontendUser $upvotedByToRemove The FrontendUser to be removed
     * @return void
     */
    public function removeUpvotedBy(FrontendUser $upvotedByToRemove): void
    {
        $this->upvotedBy->detach($upvotedByToRemove);
    }

    /**
     * Returns the upvotedBy
     *
     * @return ObjectStorage<FrontendUser> $upvotedBy
     */
    public function getUpvotedBy(): ?ObjectStorage
    {
        return $this->upvotedBy;
    }

    /**
     * Sets the upvotedBy
     *
     * @param ObjectStorage<FrontendUser> $upvotedBy
     * @return void
     */
    public function setUpvotedBy(ObjectStorage $upvotedBy): void
    {
        $this->upvotedBy = $upvotedBy;
    }

    /**
     * Adds a FrontendUser
     *
     * @param FrontendUser $downvotedBy
     * @return void
     */
    public function addDownvotedBy(FrontendUser $downvotedBy): void
    {
        $this->downvotedBy->attach($downvotedBy);
    }

    /**
     * Removes a FrontendUser
     *
     * @param FrontendUser $downvotedByToRemove The FrontendUser to be removed
     * @return void
     */
    public function removeDownvotedBy(FrontendUser $downvotedByToRemove): void
    {
        $this->downvotedBy->detach($downvotedByToRemove);
    }

    /**
     * Returns the downvotedBy
     *
     * @return ObjectStorage<FrontendUser> $downvotedBy
     */
    public function getDownvotedBy(): ?ObjectStorage
    {
        return $this->downvotedBy;
    }

    /**
     * Sets the downvotedBy
     *
     * @param ObjectStorage<FrontendUser> $downvotedBy
     * @return void
     */
    public function setDownvotedBy(ObjectStorage $downvotedBy): void
    {
        $this->downvotedBy = $downvotedBy;
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
     * Returns the createdBy
     *
     * @return FrontendUser $createdBy
     */
    public function getCreatedBy(): ?FrontendUser
    {
        return $this->createdBy;
    }

    /**
     * Sets the createdBy
     *
     * @param FrontendUser $createdBy
     * @return void
     */
    public function setCreatedBy(FrontendUser $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Get Tstamp
     *
     * @return DateTime
     */
    public function getTstamp(): ?DateTime
    {
        return $this->tstamp;
    }

    /**
     * Get Crdate
     *
     * @return DateTime
     */
    public function getCrdate(): ?DateTime
    {
        return $this->crdate;
    }

    /**
     * Returns the canEdit
     *
     * @return bool $canEdit
     */
    public function getCanEdit(): bool
    {
        return $this->canEdit;
    }

    /**
     * Sets the canEdit
     *
     * @param bool $canEdit
     * @return void
     */
    public function setCanEdit(bool $canEdit): void
    {
        $this->canEdit = $canEdit;
    }

    /**
     * Returns the canDelete
     *
     * @return bool $canDelete
     */
    public function getCanDelete(): bool
    {
        return $this->canDelete;
    }

    /**
     * Sets the canDelete
     *
     * @param bool $canDelete
     * @return void
     */
    public function setCanDelete(bool $canDelete): void
    {
        $this->canDelete = $canDelete;
    }

    /**
     * Returns the thema
     *
     * @return int $thema
     */
    public function getThema(): int
    {
        return $this->thema;
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
     * Returns the pinned
     *
     * @return bool $pinned
     */
    public function getPinned(): bool
    {
        return $this->pinned;
    }

    /**
     * Sets the pinned
     *
     * @param bool $pinned
     * @return void
     */
    public function setPinned(bool $pinned): void
    {
        $this->pinned = $pinned;
    }

    /**
     * Returns the featured
     *
     * @return bool $featured
     */
    public function getFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * Sets the featured
     *
     * @param bool $featured
     * @return void
     */
    public function setFeatured(bool $featured): void
    {
        $this->featured = $featured;
    }

    /**
     * Adds a view
     *
     * @param FrontendUser $view
     * @return void
     */
    public function addView(FrontendUser $view): void
    {
        $this->views->attach($view);
    }

    /**
     * Removes a FrontendUser
     *
     * @param FrontendUser $viewToRemove The FrontendUser to be removed
     * @return void
     */
    public function removeView(FrontendUser $viewToRemove): void
    {
        $this->views->detach($viewToRemove);
    }

    /**
     * Returns the views
     *
     * @return ObjectStorage<FrontendUser> $views
     */
    public function getViews(): ?ObjectStorage
    {
        return $this->views;
    }

    /**
     * Sets the views
     *
     * @param ObjectStorage<FrontendUser> $views
     * @return void
     */
    public function setViews(ObjectStorage $views): void
    {
        $this->views = $views;
    }

    /**
     * Adds a reaction
     *
     * @param Reactionrel $reactionRel
     * @return void
     */
    public function addReactionRel(Reactionrel $reactionRel)
    {
        $this->reactionRel->attach($reactionRel);
    }

    /**
     * Removes a reaction
     *
     * @param Reactionrel $reactionRelToRemove The Reaction to be removed
     * @return void
     */
    public function removeReactionRel(Reactionrel $reactionRelToRemove): void
    {
        $this->reactionRel->detach($reactionRelToRemove);
    }

    /**
     * Returns the reactionRel
     *
     * @return ObjectStorage<Reactionrel> $reactionRel
     */
    public function getReactionRel(): ?ObjectStorage
    {
        return $this->reactionRel;
    }

    /**
     * Sets the reactionRel
     *
     * @param ObjectStorage<Reactionrel> $reactionRel
     * @return void
     */
    public function setReactionRel(ObjectStorage $reactionRel): void
    {
        $this->reactionRel = $reactionRel;
    }

    /**
     * Returns the countedItems
     *
     * @return array $countedItems
     */
    public function getCountedItems(): array
    {
        return $this->countedItems;
    }

    /**
     * Returns the countedItems
     *
     * @param array $countedItems
     * @return void
     */
    public function setCountedItems(array $countedItems): void
    {
        $this->countedItems = $countedItems;
    }

    /**
     * Returns the userReaction
     *
     * @return string $userReaction
     */
    public function getUserReaction(): string
    {
        return $this->userReaction;
    }

    /**
     * Returns the userReaction
     *
     * @param string $userReaction
     * @return void
     */
    public function setUserReaction(string $userReaction): void
    {
        $this->userReaction = $userReaction;
    }
}
