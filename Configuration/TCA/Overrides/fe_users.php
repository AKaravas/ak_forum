<?php
defined('TYPO3_MODE') || die();

if (!isset($GLOBALS['TCA']['fe_users']['ctrl']['type'])) {
    // no type field defined, so we define it here. This will only happen the first time the extension is installed!!
    $GLOBALS['TCA']['fe_users']['ctrl']['type'] = 'tx_extbase_type';
    $tempColumnstx_akforum_fe_users = [];
    $tempColumnstx_akforum_fe_users[$GLOBALS['TCA']['fe_users']['ctrl']['type']] = [
        'exclude' => true,
        'label'   => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum.tx_extbase_type',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['',''],
                ['FrontendUser','Tx_AkForum_FrontendUser']
            ],
            'default' => 'Tx_AkForum_FrontendUser',
            'size' => 1,
            'maxitems' => 1,
        ]
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $tempColumnstx_akforum_fe_users);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    $GLOBALS['TCA']['fe_users']['ctrl']['type'],
    '',
    'after:' . $GLOBALS['TCA']['fe_users']['ctrl']['label']
);

$tmp_ak_forum_columns = [

    'reputation' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_frontenduser.reputation',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'reported_by' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_frontenduser.reported_by',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'fe_users',
            'MM' => 'tx_akforum_frontenduser_frontenduser_mm',
            'size' => 10,
            'autoSizeMax' => 30,
            'maxitems' => 9999,
            'multiple' => 0,
            'fieldControl' => [
                'editPopup' => [
                    'disabled' => false,
                ],
                'addRecord' => [
                    'disabled' => false,
                ],
                'listModule' => [
                    'disabled' => true,
                ],
            ],
        ],
    ],
    'visited_by' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_frontenduser.visited_by',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'fe_users',
            'MM' => 'tx_akforum_visitedby_frontenduser_mm',
            'size' => 10,
            'autoSizeMax' => 30,
            'maxitems' => 9999,
            'multiple' => 0,
            'fieldControl' => [
                'editPopup' => [
                    'disabled' => false,
                ],
                'addRecord' => [
                    'disabled' => false,
                ],
                'listModule' => [
                    'disabled' => true,
                ],
            ],
        ],
    ],
    'awards' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_frontenduser.awards',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_akforum_domain_model_award',
            'MM' => 'tx_akforum_award_frontenduser_mm',
            'size' => 10,
            'autoSizeMax' => 30,
            'maxitems' => 9999,
            'multiple' => 0,
            'fieldControl' => [
                'editPopup' => [
                    'disabled' => false,
                ],
                'addRecord' => [
                    'disabled' => false,
                ],
                'listModule' => [
                    'disabled' => true,
                ],
            ],
        ],
    ],
    'activity' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_frontenduser.activity',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_akforum_domain_model_activity',
            'foreign_default_sortby' => 'ORDER BY tstamp DESC',
            'foreign_field' => 'user',
            'maxitems' => 999999,
            'appearance' => [
                'collapseAll' => 0,
                'levelLinksPosition' => 'top',
                'showSynchronizationLink' => 1,
                'showPossibleLocalizationRecords' => 1,
                'showAllLocalizationLink' => 1
            ],
        ],
    ],
    'allowed_activity' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_frontenduser.allowed_activity',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'itemsProcFunc' => 'Karavas\\AkForum\\UserFunc\\UserFunc->activityItems',
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users',$tmp_ak_forum_columns);

/* inherit and extend the show items from the parent class */

if (isset($GLOBALS['TCA']['fe_users']['types']['0']['showitem'])) {
    $GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] = $GLOBALS['TCA']['fe_users']['types']['0']['showitem'];
} elseif(is_array($GLOBALS['TCA']['fe_users']['types'])) {
    // use first entry in types array
    $fe_users_type_definition = reset($GLOBALS['TCA']['fe_users']['types']);
    $GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] = $fe_users_type_definition['showitem'];
} else {
    $GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] = '';
}
$GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] .= ',--div--;LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_frontenduser,';
$GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] .= 'reputation, reported_by,visited_by';
$GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] .= ',--div--;LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tab_awards,';
$GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] .= 'awards';
$GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] .= ',--div--;LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tab_activity,';
$GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] .= 'activity';
$GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] .= ',--div--;LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tab_user_settings,';
$GLOBALS['TCA']['fe_users']['types']['Tx_AkForum_FrontendUser']['showitem'] .= 'allowed_activity';
$GLOBALS['TCA']['fe_users']['columns'][$GLOBALS['TCA']['fe_users']['ctrl']['type']]['config']['items'][] = ['LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_extbase_type.Tx_AkForum_FrontendUser','Tx_AkForum_FrontendUser'];
