<?php

namespace Karavas\AkForum\Domain\Repository;


use TYPO3\CMS\Extbase\Persistence\QueryInterface;

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
 * The repository for Forums
 */
class ActivityRepository extends Repository
{
    protected $defaultOrderings = ['tstamp' => QueryInterface::ORDER_DESCENDING];

    /**
     * @param $userId
     * @param $foreignUserId
     * @param $template
     * @return mixed|null
     */
    public function visitedUser($userId, $foreignUserId, $template)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                [
                    $query->equals('user.uid', $userId),
                    $query->equals('foreignUser.uid', $foreignUserId),
                    $query->equals('template', $template)
                ]
            )
        );
        $results = $query->execute();

        if (count($results) > 0) {
            return $results[0];
        } else {
            return null;
        }
    }

    public function findByReplyAndTemplate($userId, $replyId, $template)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                [
                    $query->equals('user.uid', $userId),
                    $query->equals('reply.uid', $replyId),
                    $query->equals('template', $template)
                ]
            )
        );
        $results = $query->execute();

        if (count($results) > 0) {
            return $results[0];
        } else {
            return null;
        }
    }

    public function findByPostAndTemplate($userId, $postId, $template)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                [
                    $query->equals('user.uid', $userId),
                    $query->equals('post.uid', $postId),
                    $query->equals('template', $template)
                ]
            )
        );
        $results = $query->execute();

        if (count($results) > 0) {
            return $results[0];
        } else {
            return null;
        }
    }

    public function findByThemaAndTemplate($userId, $themaId, $template)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                [
                    $query->equals('user.uid', $userId),
                    $query->equals('thema.uid', $themaId),
                    $query->equals('template', $template)
                ]
            )
        );
        $results = $query->execute();

        if (count($results) > 0) {
            return $results[0];
        } else {
            return null;
        }
    }
}
