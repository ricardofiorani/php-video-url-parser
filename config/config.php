<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:34
 */

return array(
    'services' => array(
        'Vimeo' => array(
            'patterns' => array(
                '#(https?://vimeo.com)/([0-9]+)#i',
                '#(https?://vimeo.com)/channels/staffpicks/([0-9]+)#i',
            ),
            'factory' => '\\RicardoFiorani\\Adapter\\Vimeo\\Factory\\VimeoServiceAdapterFactory',
        ),
        'Youtube' => array(
            'patterns' => array(
                '#(?:<\>]+href=\")?(?:http://)?((?:[a-zA-Z]{1,4}\.)?youtube.com/(?:watch)?\?v=(.{11}?))[^"]*(?:\"[^\<\>]*>)?([^\<\>]*)(?:)?#',
                '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            ),
            'factory' => '\\RicardoFiorani\\Adapter\\Youtube\\Factory\\YoutubeServiceAdapterFactory',
        ),
        'Dailymotion' => array(
            'patterns' => array(
                '#https?://www.dailymotion.com/video/([A-Za-z0-9]+)#s',
            ),
            'factory' => '\\RicardoFiorani\\Adapter\\Dailymotion\\Factory\\DailymotionServiceAdapterFactory',
        ),
        'Facebook' => array(
            'patterns' => array(
                '~\bfacebook\.com.*?\bv=(\d+)~',
                '~^https?://www\.facebook\.com/video\.php\?v=(\d+)|.*?/videos/(\d+)$~m',
                '~^https?://www\.facebook\.com/.*?/videos/(\d+)/?$~m',

            ),
            'factory' => '\\RicardoFiorani\\Adapter\\Facebook\\Factory\\FacebookServiceAdapterFactory',
        ),
    ),
    'renderer' => array(
        'name' => 'DefaultRenderer',
        'factory' => '\\RicardoFiorani\\Renderer\\Factory\\DefaultRendererFactory',
    )
);
