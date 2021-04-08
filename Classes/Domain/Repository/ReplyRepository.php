<?php


namespace Karavas\AkForum\Domain\Repository;


use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class ReplyRepository extends \Karavas\AkForum\Domain\Repository\Repository
{
    public function findByUids($uids)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->in('uid', $uids)
        );
        return $query->execute();
    }

    public function findByUser($id)
    {
        $query = $this->createQuery();
        $query->matching($query->equals('createdBy.uid', $id));
        return $query->execute();
    }

    public function findByPostId($id, $limit=0, $offset = 0)
    {
        $query = $this->createQuery();
        $query->matching($query->equals('post', $id));
        return $query->execute();
    }
}