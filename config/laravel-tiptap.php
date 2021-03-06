<?php

return [

    'images' => [
        'disk' => env('FILESYSTEM_DRIVER', 's3'),
        'maxSize' => 1000,
        'middleware' => [
            'web',
            'auth', // require login
        ],
    ],

];
