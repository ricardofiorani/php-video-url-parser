<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 19:24
 */

namespace RicardoFiorani\Adapter\Youtube\Factory;


use RicardoFiorani\Adapter\Factory\CallableFactoryInterface;
use RicardoFiorani\Adapter\Youtube\YoutubeServiceAdapter;

class YoutubeServiceAdapterFactory implements CallableFactoryInterface
{

    /**
     * @param string $url
     * @param string $regex
     * @return YoutubeServiceAdapter
     */
    public function __invoke($url, $regex)
    {
        $youtubeVideoAdapter = new YoutubeServiceAdapter($url, $regex);

        return $youtubeVideoAdapter;
    }
}
