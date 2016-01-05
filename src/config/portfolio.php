<?php

return [

    // Route URL's
    'routes' => [

        // Publicly Accessible Pages
        'public' => [
            'index' => 'portfolio',
            'show' => 'portfolio/{slug}',
            'showPage' => '/portfolio/{slug}/{pageSlug}',
        ],

        // Admin area
        'admin' => [
            'prefix' => 'admin',
            'home' => '/'
        ],

        // API Routes
        // !! These values should not be changed. If they must be changed, the administration area javascript will need to be re-built !!
        'api' => [
            'prefix' => 'api/',
            'project' => 'project',
            'section' => 'section',
            'tag' => 'tag',
            'tagSearch' => 'tag/search',
            'page' => 'page'
        ]
    ]

];
