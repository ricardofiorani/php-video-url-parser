<?php

return [
    'services' => [
        'Vimeo' => [
            'patterns' => [
                '#(https?://vimeo.com)/([0-9]+)#i',
                '#(https?://vimeo.com)/channels/staffpicks/([0-9]+)#i',
            ],
            'factory' => \RicardoFiorani\VideoUrlParser\Adapter\Vimeo\Factory\VimeoServiceAdapterFactory::class,
        ],
        'Youtube' => [
            'patterns' => [
                '#(?:<\>]+href=\")?(?:http://)?((?:[a-zA-Z]{1,4}\.)?youtube.com/(?:watch)?\?v=(.{11}?))[^"]*(?:\"[^\<\>]*>)?([^\<\>]*)(?:)?#',
                '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            ],
            'factory' => \RicardoFiorani\VideoUrlParser\Adapter\Youtube\Factory\YoutubeServiceAdapterFactory::class,
        ],
        'Dailymotion' => [
            'patterns' => [
                '#https?://www.dailymotion.com/video/([A-Za-z0-9]+)#s',
            ],
            'factory' => \RicardoFiorani\VideoUrlParser\Adapter\Dailymotion\Factory\DailymotionServiceAdapterFactory::class,
        ],
        'Facebook' => [
            'patterns' => [
                '~\bfacebook\.com.*?\bv=(\d+)~',
                '~^https?://www\.facebook\.com/video\.php\?v=(\d+)|.*?/videos/(\d+)$~m',
                '~^https?://www\.facebook\.com/.*?/videos/(\d+)/?$~m',

            ],
            'factory' => \RicardoFiorani\VideoUrlParser\Adapter\Facebook\Factory\FacebookServiceAdapterFactory::class,
        ],
    ],
    'renderer' => [
        'name' => 'DefaultRenderer',
        'factory' => \RicardoFiorani\VideoUrlParser\Renderer\Factory\DefaultRendererFactory::class,
    ]
];
