<?php
namespace Karavas\AkForum\Domain\Repository;


use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

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
class AwardRepository extends \Karavas\AkForum\Domain\Repository\Repository
{
    public function findAllByAwardId($awardId) {
        $query = $this->createQuery();
        $query->matching($query->equals('reason', $awardId));
        $query->setOrderings(
            [
                'amount' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
            ]
        );
        return $query->execute();
    }
}
