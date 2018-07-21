<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [],
    'components' => [
        'authManager' => [
            'class' => 'kak\rbac\components\DbManager',
            'defaultRoles' => [
                'guest',
                'user'
            ],
        ],
        'user' => [
            'class' => 'common\components\User',
            'identityClass' => 'common\models\m\UserModel',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
        'i18n' => [
            'translations' => [],
        ],
    ],
];
