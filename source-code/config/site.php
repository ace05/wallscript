<?php

return [
    'siteName' => 'SocialWall',
	'images' =>[
        'paths' => [
            'input' => 'public/uploads/',
            'output' => 'app/storage/cache/images'
        ],
        'sizes' => [
            'small' => [
                'width' => 25,
                'height' => 25
            ],
            'likeThumb' => [
                'width' => 45,
                'height' => 45
            ],
            'profileThumb' => [
                'width' => 100,
                'height' => 100
            ],
            'medium' => [
                'width' => 50,
                'height' => 50
            ],
            'postSlider' => [
                'width' => 725,
                'height' => 410
            ],
            'modelBox' => [
                'width' => 725,
                'height' => 410
            ],
            'commentImage' => [
                'width' => 200,
                'height' => 180
            ],
            'postGallery' => [
                'width' => 226,
                'height' => 226
            ],
            'userNavThumb' => [
                'width' => 180,
                'height' => 180
            ],
            'coverThumb' => [
                'width' => 920,
                'height' => 300
            ],
            'timelineThumb' => [
                'width' => 450,
                'height' => 450
            ]
        ]
    ],
    'timthumb' => [
        'subfolder'             => '',  
        'debug_on'              => false,
        'debug_level'           => 3,
        'file_cache_enabled'    => false,
        'file_cache_directory'  => base_path('storage/timthumb/'),
        'not_found_image'       => '',
        'error_image'           => '',
        'png_is_transparent'    => true
    ],
    'max_upload_file_size' => 2048
];