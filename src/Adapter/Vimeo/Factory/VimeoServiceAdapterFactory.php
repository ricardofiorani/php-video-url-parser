<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:56
 */

namespace RicardoFiorani\Adapter\Vimeo\Factory;


use RicardoFiorani\Adapter\Factory\CallableFactoryInterface;
use RicardoFiorani\Adapter\Vimeo\VimeoServiceAdapter;

class VimeoServiceAdapterFactory implements CallableFactoryInterface
{
    /**
     * @param string $url
     * @param string $pattern
     * @return VimeoServiceAdapter
     */
    public function __invoke($url, $pattern)
    {
        $adapter = new VimeoServiceAdapter($url, $pattern);

        return $adapter;
    }
}
