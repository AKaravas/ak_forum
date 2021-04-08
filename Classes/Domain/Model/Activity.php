<?php
declare(strict_types=1);

namespace Karavas\AkForum\Domain\Model;


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
 * Activity
 */
class Activity extends AbstractEntity
{

    /**
     * @var int
     */
    protected $tstamp = 0;

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * template
     *
     * @var string
     */
    protected $template = '';

    /**
     * subtitle
     *
     * @var string
     */
    protected $subtitle = '';

    /**
     * voteAction
     *
     * @var string
     */
    protected $voteAction = '';

    /**
     * user
     *
     * @var FrontendUser
     */
    protected $user;

    /**
     * foreignUser
     *
     * @var FrontendUser
     *
     */
    protected $foreignUser;

    /**
     * reply
     *
     * @var Reply
     *
     */
    protected $reply;

    /**
     * post
     *
     * @var Post
     *
     */
    protected $post;

    /**
     * reaction
     *
     * @var Reactionrel
     *
     */
    protected $reaction;

    /**
     * thema
     *
     * @var Thema
     */
    protected $thema;

    /**
     * Topic
     *
     * @var Topic
     */
    protected $topic;

    /**
     * @return Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    /**
     * @return Reactionrel
     */
    public function getReaction(): ?Reactionrel
    {
        return $this->reaction;
    }

    /**
     * @param Reactionrel $reaction
     */
    public function setReaction(Reactionrel $reaction): void
    {
        $this->reaction = $reaction;
    }
    /**
     * @return Thema
     */
    public function getThema(): ?Thema
    {
        return $this->thema;
    }

    /**
     * @param Thema $thema
     */
    public function setThema(Thema $thema): void
    {
        $this->thema = $thema;
    }

    /**
     * @return Reply
     */
    public function getReply(): ?Reply
    {
        return $this->reply;
    }

    /**
     * @param Reply $reply
     */
    public function setReply(Reply $reply): void
    {
        $this->reply = $reply;
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
     * Returns the subtitle
     *
     * @return string $subtitle
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * Sets the subtitle
     *
     * @param string $subtitle
     * @return void
     */
    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

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
     * @return FrontendUser
     */
    public function getForeignUser(): ?FrontendUser
    {
        return $this->foreignUser;
    }

    /**
     * @param FrontendUser $foreignUser
     */
    public function setForeignUser(FrontendUser $foreignUser): void
    {
        $this->foreignUser = $foreignUser;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return int
     */
    public function getTstamp(): int
    {
        return $this->tstamp;
    }

    /**
     * @param int $tstamp
     */
    public function setTstamp(int $tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    /**
     * @return string
     */
    public function getVoteAction(): string
    {
        return $this->voteAction;
    }

    /**
     * @param string $voteAction
     */
    public function setVoteAction(string $voteAction): void
    {
        $this->voteAction = $voteAction;
    }

    /**
     * @return Topic
     */
    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    /**
     * @param Topic $topic
     */
    public function setTopic(Topic $topic): void
    {
        $this->topic = $topic;
    }

}
