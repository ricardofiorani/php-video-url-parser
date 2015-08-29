<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:57
 */

namespace RicardoFiorani\Container\Factory;


use RicardoFiorani\Adapter\VideoAdapterInterface;

interface CallableFactoryInterface
{
    /**
     * @param string $url
     * @param string $patternMatched
     * @return VideoAdapterInterface
     */
    public function __invoke($url, $patternMatched);
}