<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 30/08/2015
 * Time: 14:40.
 */
namespace RicardoFiorani\Adapter\Dailymotion\Factory;

use RicardoFiorani\Adapter\Dailymotion\DailymotionServiceAdapter;
use RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class DailymotionServiceAdapterFactory implements CallableServiceAdapterFactoryInterface
{
    /**
     * @param string                 $url
     * @param string                 $pattern
     * @param EmbedRendererInterface $renderer
     *
     * @return VideoAdapterInterface
     */
    public function __invoke($url, $pattern, EmbedRendererInterface $renderer)
    {
        $dailyMotionServiceAdapter = new DailymotionServiceAdapter($url, $pattern, $renderer);

        return $dailyMotionServiceAdapter;
    }
}
