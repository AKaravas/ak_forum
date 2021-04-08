<?php
namespace Karavas\AkForum\Domain\Repository;

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
 * The repository for Posts
 */
class PostRepository extends \Karavas\AkForum\Domain\Repository\Repository
{
    public function findByUser($id)
    {
        $query = $this->createQuery();
        $query->matching($query->equals('createdBy.uid', $id));
        return $query->execute();
    }
}
