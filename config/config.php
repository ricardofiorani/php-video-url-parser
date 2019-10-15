<?php declare(strict_types=1);

use RicardoFiorani\Adapter\Dailymotion\DailymotionServiceAdapter;
use RicardoFiorani\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\Adapter\Vimeo\VimeoServiceAdapter;
use RicardoFiorani\Adapter\Youtube\YoutubeServiceAdapter;
use RicardoFiorani\Renderer\Factory\DefaultRendererFactory;
use RicardoFiorani\Adapter\Facebook\Factory\FacebookServiceAdapterFactory;
use RicardoFiorani\Adapter\Dailymotion\Factory\DailymotionServiceAdapterFactory;
use RicardoFiorani\Adapter\Youtube\Factory\YoutubeServiceAdapterFactory;
use RicardoFiorani\Adapter\Vimeo\Factory\VimeoServiceAdapterFactory;

return [
    'services' => [
        VimeoServiceAdapter::SERVICE_NAME => [
            'patterns' => [
                '#(https?://vimeo.com)/([0-9]+)#i',
                '#(https?://vimeo.com)/channels/staffpicks/([0-9]+)#i',
            ],
            'factory' => VimeoServiceAdapterFactory::class,
        ],
        YoutubeServiceAdapter::SERVICE_NAME => [
            'patterns' => [
                '#(?:<\>]+href=\")?(?:http://)?((?:[a-zA-Z]{1,4}\.)?youtube.com/(?:watch)?\?v=(.{11}?))[^"]*(?:\"[^\<\>]*>)?([^\<\>]*)(?:)?#',
                '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            ],
            'factory' => YoutubeServiceAdapterFactory::class,
        ],
        DailymotionServiceAdapter::SERVICE_NAME => [
            'patterns' => [
                '#https?://www.dailymotion.com/video/([A-Za-z0-9]+)#s',
            ],
            'factory' => DailymotionServiceAdapterFactory::class,
        ],
        FacebookServiceAdapter::SERVICE_NAME => [
            'patterns' => [
                '~\bfacebook\.com.*?\bv=(\d+)~',
                '~^https?://www\.facebook\.com/video\.php\?v=(\d+)|.*?/videos/(\d+)$~m',
                '~^https?://www\.facebook\.com/.*?/videos/(\d+)/?$~m',
            ],
            'factory' => FacebookServiceAdapterFactory::class,
        ],
    ],
    'renderer' => [
        'name' => 'DefaultRenderer',
        'factory' => DefaultRendererFactory::class,
    ]
];
