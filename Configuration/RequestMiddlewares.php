<?php

return [
    'frontend' => [
        'ak-forum-logged' => [
            'target' => \Karavas\AkForum\Middlewares\FrontEnd\UserLoggedInMiddleware::class,
            'after' => [
                'typo3/cms-extbase/signal-slot-deprecator',
            ],
        ],
        'ak-forum-ajax' => [
            'target' => \Karavas\AkForum\Middlewares\FrontEnd\AjaxMiddleware::class
        ]
    ],
    'backend' => [
        'ak-forum-remove-plugins' => [
            'target' => \Karavas\AkForum\Middlewares\Backend\PluginConfigurationMiddleware::class,
        ]
    ]
];