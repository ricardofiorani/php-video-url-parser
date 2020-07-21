<?php declare(strict_types = 1);
namespace RicardoFiorani\VideoUrlParser\Adapter\Dailymotion\Factory;

use RicardoFiorani\VideoUrlParser\Adapter\Dailymotion\DailymotionServiceAdapter;
use RicardoFiorani\VideoUrlParser\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\VideoUrlParser\Adapter\VideoAdapterInterface;
use RicardoFiorani\VideoUrlParser\Renderer\EmbedRendererInterface;

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
