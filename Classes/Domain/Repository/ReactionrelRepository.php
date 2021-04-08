<?php


namespace Karavas\AkForum\Domain\Repository;


class ReactionrelRepository extends Repository
{
    public function findByUserAndPost($user, $post)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd([
                $query->equals('user.uid', $user->getUid()),
                $query->equals('post', $post->getUid())
            ])
        );
        return $query->execute()[0];
    }

    public function findByUserAndReply($user, $reply)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd([
                $query->equals('user.uid', $user->getUid()),
                $query->equals('reply', $reply->getUid())
            ])
        );
        return $query->execute()[0];
    }

    public function findReactionsByNamePost($post, $reaction)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd([
                $query->equals('reaction.name', $reaction),
                $query->equals('post', $post->getUid())
            ])
        );
        return $query->execute();
    }
    public function findReactionsByNameReply($reply, $reaction)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd([
                $query->equals('reaction.name', $reaction),
                $query->equals('reply', $reply->getUid())
            ])
        );
        return $query->execute();
    }

    public function findReactionsByUser($userId)
    {
        $query = $this->createQuery();
        $query->matching( $query->equals('user.uid', $userId));
        return $query->execute();
    }
}