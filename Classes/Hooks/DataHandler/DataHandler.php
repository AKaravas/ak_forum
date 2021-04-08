<?php


namespace Karavas\AkForum\Hooks\DataHandler;

use Karavas\AkForum\Domain\Model\Thema;
use Karavas\AkForum\Domain\Repository\ForumRepository;
use Karavas\AkForum\Domain\Repository\ThemaRepository;
use Karavas\AkForum\Domain\Repository\TopicRepository;
use Karavas\AkForum\Helper\Helper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Class DataHandler
 * @package Karavas\AkForum\Hooks\DataHandler
 */
class DataHandler
{
    /**
     *
     * @var Helper
     */
    protected $helper;

    /**
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $pObj
     */
    public function processDatamap_afterAllOperations(\TYPO3\CMS\Core\DataHandling\DataHandler $pObj): void
    {
        $tables = $pObj->datamap;
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        foreach ($tables as $key => $table) {
            switch ($key) {
                case 'tx_akforum_domain_model_forum':
                    $forumRepository = GeneralUtility::makeInstance(ForumRepository::class);
                    $this->addHmac($forumRepository, $table, $persistenceManager, $pObj);
                    break;
                case 'tx_akforum_domain_model_thema':
                    $themaRepository = GeneralUtility::makeInstance(ThemaRepository::class);
                    $this->addHmac($themaRepository, $table, $persistenceManager, $pObj);
                    break;
                case 'tx_akforum_domain_model_topic':
                    $topicRepository = GeneralUtility::makeInstance(TopicRepository::class);
                    $this->addHmac($topicRepository, $table, $persistenceManager, $pObj);
                    break;
            }
        }
    }

    /**
     * @param $repository
     * @param $entries
     * @param $persistenceManager
     * @param $pObj
     */
    public function addHmac($repository, $entries, $persistenceManager, $pObj): void
    {
        $this->helper = GeneralUtility::makeInstance(Helper::class);
        $extSettings = $this->helper->loadPluginSettings();
        $secretPhrase = $extSettings['forum']['global']['encryptionPhrase'];

        foreach ($entries as $key => $entry) {
            if (is_int($key)) {
                $objId = $key;
            }
            else {
                $objId = $pObj->substNEWwithIDs[$key];
            }

            $object = $repository->findByUid($objId);
            if (empty($object->getHmac())) {
                $hmac = GeneralUtility::hmac($object->getUid(), $secretPhrase);
                $object->setHmac($hmac);
                $repository->update($object);
                $persistenceManager->persistAll();
            }
        }
    }
}