<?php
declare(strict_types = 1);

return [
    \Karavas\AkForum\Domain\Model\FrontendUser::class => [
        'tableName' => 'fe_users',
    ],
    \Karavas\AkForum\Domain\Model\BackendUser::class => [
        'tableName' => 'be_users',
    ],
    \Karavas\AkForum\Domain\Model\Reactionrel::class => [
        'tableName' => 'tx_akforum_domain_model_reactionrel',
        'properties' => [
            'creationDate' => [
                'fieldName' => 'crdate'
            ]
        ]
    ],
];
