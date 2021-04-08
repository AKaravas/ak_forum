<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,subtitle',
        'iconfile' => 'EXT:ak_forum/Resources/Public/Icons/tx_akforum_domain_model_activity.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, subtitle, user, foreign_user, reply, post, reaction, thema, topic',
    ],
    'types' => [
        '0' => [
            'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, subtitle, template, user, foreign_user, reply, post, thema, topic, reaction,vote_action'
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_akforum_domain_model_activity',
                'foreign_table_where' => 'AND {#tx_akforum_domain_model_activity}.{#pid}=###CURRENT_PID### AND {#tx_akforum_domain_model_activity}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'subtitle' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.subtitle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'template' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.template',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'vote_action' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.vote_action',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'user' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.user',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'foreign_user' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.foreign_user',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['', 0],
                ],
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'reply' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.reply',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_akforum_domain_model_reply',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'post' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.post',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_akforum_domain_model_post',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'thema' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.thema',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_akforum_domain_model_thema',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'topic' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.topic',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_akforum_domain_model_topic',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'reaction' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_activity.reaction',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_akforum_domain_model_reactionrel',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
    ],
];
