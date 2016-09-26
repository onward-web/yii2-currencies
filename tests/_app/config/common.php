<?php

return [
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'aliases' => [
        '@jarrus90/Currencies' => dirname(dirname(dirname(__DIR__))),
        '@tests' => dirname(dirname(__DIR__)),
        '@vendor' => VENDOR_DIR,
    ],
    'bootstrap' => ['jarrus90\Currencies\Bootstrap'],
    'modules' => [
        'currencies' => [
            'class' => 'jarrus90\Currencies\Module'
        ],
    ],
    'modules' => [
        'multilang' => [
            'class' => 'jarrus90\Currencies\Module',
            'controllerMap' => [
                'admin' => [
                    'class' => 'jarrus90\Currencies\Controllers\AdminController',
                    'behaviors' => function(){return [];}
                ]
            ]
        ],
    ],
    'params' => [],
];