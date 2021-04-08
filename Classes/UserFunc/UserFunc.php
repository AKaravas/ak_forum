<?php


namespace Karavas\AkForum\UserFunc;

use Karavas\AkForum\Helper\Helper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class UserFunc
{
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * UserFunc constructor.
     * @param Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper =$helper;
    }

    /**
     * @param array $config
     * @return array
     */
    public function activityItems(array $config): array
    {
        $settings = $this->helper->loadPluginSettings();
        $activityItems = $settings['forum']['global']['activity'];
        $itemList = [];
        foreach ($activityItems as $key => $activity) {
            if ($key === 'all') {
                continue;
            }
            $itemList[] = [LocalizationUtility::translate('allowed_activity.'.$key, 'AkForum'), $key];
        }
        $config['items'] = $itemList;

        return $config;
    }
}