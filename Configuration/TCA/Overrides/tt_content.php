<?php

call_user_func(
    function ()
    {
        $extensionActions = [
            '\Karavas\AkForum\Controller\ForumController::class' => [
                'actions' =>  [
                    'show','postFeed','replyFeed'
                ],
                'object' => 'forum'
            ],
            '\Karavas\AkForum\Controller\TopicController::class' => [
                'actions' => [
                    'list', 'show'
                ],
                'object' => 'topic'
            ],
            '\Karavas\AkForum\Controller\ThemaController::class' => [
                'actions' => [
                    'show'
                ],
                'object' => 'thema'
            ],
            '\Karavas\AkForum\Controller\ReplyController::class' => [
                'actions' => [
                    'new', 'create', 'edit', 'update', 'delete'
                ],
                'object' => 'reply'
            ],
            '\Karavas\AkForum\Controller\PostController::class' => [
                'actions' => [
                    'show', 'new', 'create', 'edit', 'update', 'delete'
                ],
                'object' => 'post'
            ],
            '\Karavas\AkForum\Controller\AjaxController::class' => [
                'actions' => [
                    'quote', 'getCurrentCount', 'removeCurrentQuotes', 'toggleFollow', 'reaction', 'getReactionList', 'votes'
                ],
                'object' => 'ajax',
                'no-plugin' => 1
            ],
            '\Karavas\AkForum\Controller\UserController::class' => [
                'actions' => [
                    'profile', 'userSettings'
                ],
                'object' => 'user'
            ],
        ];
        foreach ($extensionActions as $key => $forumAction) {
            if ($forumAction['no-plugin'] !== 1) {
                foreach ($forumAction['actions'] as $action) {
                    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
                        'Karavas.AkForum',
                        $forumAction['object'].'_'. $action,
                        'LLL:EXT:ak_forum/Resources/Private/Language/locallang_db.xlf:tx_ak_forum_'.$forumAction['object'].'_'. $action.'.name',
                        'EXT:ak_forum/Resources/Public/Icons/'.$forumAction['object'].'_'. $action
                    );
                }
            }
        }
    }
);