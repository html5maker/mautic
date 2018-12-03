<?php

return [
    'name' => 'Bannernow',
    'description' => 'Integration with Bannernow',
    'author' => 'Bannernow Inc',
    'version' => '1.0.0',
    'routes' => [
        'main' => [
            'mautic_bannernow_iframe' => [
                'path' => '/bannernow/iframe',
                'controller' => 'MauticBannernowBundle:Default:iframe',
            ],
            'mautic_bannernow_admin' => [
                'path' => '/bannernow/admin',
                'controller' => 'MauticBannernowBundle:Default:admin',
            ],
            'mautic_bannernow_world' => [
                'path' => '/bannernow/{world}',
                'controller' => 'MauticBannernowBundle:Default:world',
                'defaults' => [
                    'world' => 'earth'
                ],
                'requirements' => [
                    'world' => 'earth|mars'
                ]
            ],
        ],
        'public' => [
            'mautic_bannernow_hello' => [
                'path' => '/bannernow/hello',
                'controller' => 'MauticBannernowBundle:Default:hello'
            ],
        ],
        'api' => [
            'mautic_bannernow_api' => [
                'path' => '/bannernow/api/hello',
                'controller' => 'MauticBannernowBundle:Api:hello',
                'method' => 'GET'
            ]
        ],
    ],
    'menu' => [
        'main' => [
            'mautic.bannernow.iframe' => [
                'iconClass' => 'fa-globe',
                'route'    => 'mautic_bannernow_iframe',
                'priority' => 1000,
                'checks'   => [
                    'integration' => [
                        'Bannernow' => [
                            'enabled' => true,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
