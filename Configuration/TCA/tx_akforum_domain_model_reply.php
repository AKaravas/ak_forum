<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply',
        'label' => 'body',
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
        'searchFields' => 'body',
        'iconfile' => 'EXT:ak_forum/Resources/Public/Icons/tx_akforum_domain_model_reply.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, body,  upvoted_by, downvoted_by, reported_by, created_by',
    ],
    'types' => [
        '1' => [
            'showitem' =>
                'sys_language_uid, l10n_parent, l10n_diffsource, hidden, body, created_by,
                --div--;LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tab_actions,
                    liked_by, disliked_by, reported_by, 
                --div--;LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tab_reactions,
                    reaction_rel,
                --div--;LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tab_votes,
                    upvoted_by, downvoted_by,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, 
                    starttime, endtime'
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
                'foreign_table' => 'tx_akforum_domain_model_reply',
                'foreign_table_where' => 'AND {#tx_akforum_domain_model_reply}.{#pid}=###CURRENT_PID### AND {#tx_akforum_domain_model_reply}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'passthrough',
            ]
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

        'body' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply.body',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'can_edit' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply.can_edit',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
            ]
        ],
        'can_delete' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply.can_delete',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
            ]
        ],
        'times_edited' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply.times_edited',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'created_by' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_post.created_by',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'minitems' => 0,
                'maxitems' => 1,
            ],

        ],
        'upvoted_by' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply.liked_by',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'fe_users',
                'MM' => 'tx_akforum_reply_upvotedby_frontenduser_mm',
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
        'downvoted_by' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply.disliked_by',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'fe_users',
                'MM' => 'tx_akforum_reply_downvotedby_frontenduser_mm',
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
        'reported_by' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply.reported_by',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'fe_users',
                'MM' => 'tx_akforum_reply_reportedby_frontenduser_mm',
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
        'reaction_rel' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply.reaction_rel',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_akforum_domain_model_reactionrel',
                'foreign_field' => 'reply',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
        'user' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_akforum_domain_model_reply.user',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingleBox',
                'foreign_table' => 'fe_users',
                'MM' => 'tx_akforum_reply_user_frontenduser_mm',
            ],
            
        ],
        'hmac' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:hmac',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'post' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
