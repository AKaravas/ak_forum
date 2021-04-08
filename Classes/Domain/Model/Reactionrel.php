<?php
declare(strict_types=1);

namespace Karavas\AkForum\Domain\Model;


use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

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
class Reactionrel extends AbstractEntity
{

    /**
     * @var DateTime
     */
    protected $creationDate;

    /**
     * reaction
     *
     * @var Reaction
     *
     */
    protected $reaction;

    /**
     * reaction
     *
     * @var FrontendUser
     *
     */
    protected $user;
    /**
     * Returns the user
     *
     * @return FrontendUser $user
     */
    public function getUser(): ?FrontendUser
    {
        return $this->user;
    }

    /**
     * Sets the user
     *
     * @param FrontendUser $user
     * @return void
     */
    public function setUser(FrontendUser $user): void
    {
        $this->user = $user;
    }

    /**
     * Returns the reaction
     *
     * @return Reaction $reaction
     */
    public function getReaction(): ?Reaction
    {
        return $this->reaction;
    }

    /**
     * Sets the reaction
     *
     * @param Reaction $reaction
     * @return void
     */
    public function setReaction(Reaction $reaction): void
    {
        $this->reaction = $reaction;
    }

    public function getCreationDate() : DateTime
    {
        return $this->creationDate;
    }
}
