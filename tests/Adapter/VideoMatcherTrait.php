<?php declare(strict_types=1);

namespace RicardoFioraniTests\Adapter;

use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Exception\ServiceNotAvailableException;
use RicardoFiorani\Matcher\VideoServiceMatcher;

trait VideoMatcherTrait
{
    /**
     * @throws ServiceNotAvailableException
     */
    public function parse(string $url): VideoAdapterInterface
    {
        $videoParser = new VideoServiceMatcher();

        return $videoParser->parse($url);
    }
}
