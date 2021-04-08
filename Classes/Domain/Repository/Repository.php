<?php


namespace Karavas\AkForum\Domain\Repository;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class Repository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function initializeObject() {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}