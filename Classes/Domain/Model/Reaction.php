<?php
declare(strict_types=1);

namespace Karavas\AkForum\Domain\Model;


use TYPO3\CMS\Extbase\Domain\Model\FileReference;
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
 * Forum
 */
class Reaction extends AbstractEntity
{

    /**
     * name
     * 
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $name = '';

    /**
     *  reactionIcon
     *
     * @var string
     */
    protected $reactionIcon = '';

    /**
     *  reputation
     *
     * @var int
     */
    protected $reputation = '';

    /**
     * reactionImage
     *
     * @var FileReference
     */
    protected $reactionImage;

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
     * Returns the reactionIcon
     *
     * @return string $reactionIcon
     */
    public function getReactionIcon(): string
    {
        return $this->reactionIcon;
    }

    /**
     * Sets the reactionIcon
     *
     * @param string $reactionIcon
     * @return void
     */
    public function setReactionIcon(string $reactionIcon): void
    {
        $this->reactionIcon = $reactionIcon;
    }

    /**
     * Returns the reactionImage
     *
     * @return FileReference $reactionImage
     */
    public function getReactionImage(): ?FileReference
    {
        return $this->reactionImage;
    }

    /**
     * Sets the reactionImage
     *
     * @param FileReference $reactionImage
     * @return void
     */
    public function setReactionImage(FileReference $reactionImage): void
    {
        $this->reactionImage = $reactionImage;
    }

    /**
     * Returns the reputation
     *
     * @return int $reputation
     */
    public function getReputation(): int
    {
        return $this->reputation;
    }

    /**
     * Sets the reputation
     *
     * @param int $reputation
     * @return void
     */
    public function setReputation(int $reputation): void
    {
        $this->reputation = $reputation;
    }
}
