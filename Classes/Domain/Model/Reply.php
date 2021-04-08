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
 * Reply
 */
class Reply extends AbstractEntity
{

    /**
     * body
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $body = '';

    /**
     * canEdit
     *
     * @var boolean
     */
    protected $canEdit = false;

    /**
     * @var DateTime
     */
    protected $tstamp;

    /**
     * @var DateTime
     */
    protected $crdate;

    /**
     * canEdit
     *
     * @var boolean
     */
    protected $canDelete = false;

    /**
     * timesEdited
     *
     * @var int
     */
    protected $timesEdited= 0;

    /**
     * @var string
     */
    protected $hmac = '';

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
     * reportedBy
     * 
     * @var ObjectStorage<FrontendUser>
     */
    protected $reportedBy;

    /**
     * createdBy
     *
     * @var FrontendUser
     */
    protected $createdBy;

    /**
     * post
     *
     * @var Post
     *
     */
    protected $post;

    /**
     * reactionRel
     *
     * @var ObjectStorage<Reactionrel>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $reactionRel;

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
        $this->upvotedBy = new ObjectStorage();
        $this->downvotedBy = new ObjectStorage();
        $this->reportedBy = new ObjectStorage();
        $this->reactionRel = new ObjectStorage();
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
     * gets the post
     *
     * @return Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * Adds a reaction
     *
     * @param Reactionrel $reactionRel
     * @return void
     */
    public function addReactionRel(Reactionrel $reactionRel): void
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
