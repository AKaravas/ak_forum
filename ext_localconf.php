<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        $extensionActions = [
            'Karavas\\AkForum\\Controller\\ForumController' => [
                'actions' =>  [
                    'show','postFeed','replyFeed'
                ],
                'object' => 'forum'
            ],
            'Karavas\\AkForum\\Controller\\TopicController' => [
                'actions' => [
                    'list', 'show'
                ],
                'object' => 'topic'
            ],
            'Karavas\\AkForum\\Controller\\ThemaController' => [
                'actions' => [
                    'show'
                ],
                'object' => 'thema'
            ],
            'Karavas\\AkForum\\Controller\\ReplyController' => [
                'actions' => [
                    'new', 'create', 'edit', 'update', 'delete'
                ],
                'object' => 'reply'
            ],
            'Karavas\\AkForum\\Controller\\PostController' => [
                'actions' => [
                    'show', 'new', 'create', 'edit', 'update', 'delete'
                ],
                 'object' => 'post'
            ],
            'Karavas\\AkForum\\Controller\\AjaxController' => [
                'actions' => [
                    'quote', 'getCurrentCount', 'removeCurrentQuotes', 'toggleFollow', 'reaction', 'getReactionList', 'votes'
                ],
                'object' => 'ajax',
                'no-plugin' => 1
            ],
            'Karavas\\AkForum\\Controller\\UserController' => [
                'actions' => [
                    'profile', 'userSettings'
                ],
                'object' => 'user'
            ],
        ];

        foreach ($extensionActions as $key => $forumAction) {
                foreach ($forumAction['actions'] as $action) {
                    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
                        'Karavas.AkForum',
                        $forumAction['object'].'_'. $action,
                        [
                            $key => $action
                        ],
                        [
                            $key => $action
                        ],
                    );
                    if ($forumAction['no-plugin'] !== 1) {
                        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                            'mod {
                        wizards.newContentElement.wizardItems.forum {
                            after = plugins
                            header = Forum
                            elements {
                                akforum_'.$forumAction['object'].'_'. $action.' {
                                    iconIdentifier = '.$forumAction['object'].'_'. $action.'
                                    title = LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_ak_forum_'.$forumAction['object'].'_'. $action.'.name
                                    description = LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_ak_forum_'.$forumAction['object'].'_'. $action.'.description
                                    tt_content_defValues {
                                        CType = list
                                        list_type = akforum_'.$forumAction['object'].'_'. $action.'
                                    }
                                }
                            }
                            show = *
                        }
                    }'
                    );
                }
            }
        }

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

			$iconRegistry->registerIcon(
				'ak_forum-plugin-akforum',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:ak_forum/Resources/Public/Icons/user_plugin_akforum.svg']
			);

    }
);
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['ak_forum'] = \Karavas\AkForum\Hooks\DataHandler\DataHandler::class;
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['ak_forum']  = \Karavas\AkForum\Hooks\DataHandler\DataHandler::class;

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['forum'] = 'EXT:ak_forum/Configuration/RTE/ForumRTE.yaml';

