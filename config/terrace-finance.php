<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Terrace Finance Sidebar Menu
    |--------------------------------------------------------------------------
    | Keep your sidebar maintainable by defining it as data.
    |
    | Supported item types:
    | - section: ['type' => 'section', 'title' => 'Components']
    | - link:    ['type' => 'link', 'title' => 'Dashboard', 'icon' => 'fas fa-home', 'route' => 'dashboard']
    | - group:   ['type' => 'group', 'title' => 'Base', 'icon' => 'fas fa-layer-group', 'key' => 'base', 'children' => [...]]
    |
    */
    'menu' => [
        [
            'type' => 'link',
            'title' => 'Dashboard',
            'icon' => 'fas fa-home',
            'route' => 'dashboard',
        ],

        ['type' => 'section', 'title' => 'Components'],

        [
            'type' => 'group',
            'title' => 'Example Pages',
            'icon' => 'fas fa-layer-group',
            'key' => 'examples',
            'children' => [
                ['title' => 'Users (API)', 'route' => 'api.users.index'],
                ['title' => 'Starter Page', 'route' => 'starter'],
            ],
        ],
    ],
];
