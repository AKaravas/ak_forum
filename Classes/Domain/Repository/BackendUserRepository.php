<?php


namespace Karavas\AkForum\Domain\Repository;


use Karavas\AkForum\Domain\Model\BackendUser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class BackendUserRepository extends \TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository
{
    public function initializeObject() : void
    {
        $querySettings =  GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(FALSE);
    }

    /**
     *
     * @param string String containing uid
     * @return array QueryResultInterface
     */
    public function findByGroupId($uid)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('backendUserGroups.uid', $uid)
        );
        return $query->execute();
    }
}