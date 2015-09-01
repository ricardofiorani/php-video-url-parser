# Functional Example of Registering a DailyMotion Service

## DailyMotionServiceAdapter Class (The Service Adapter Itself)
```php
<?php
namespace MyVendor\ServiceAdapter;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Renderer\EmbedRendererInterface;

//Your service Adapter must implement VideoAdapterInterface or Extend AbstractServiceAdapter
class DailyMotionServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_DEFAULT = 'thumbnail';

    /**
     * AbstractVideoAdapter constructor.
     * @param string $url
     * @param string $pattern
     * @param EmbedRendererInterface $renderer
     */
    public function __construct($url, $pattern, EmbedRendererInterface $renderer)
    {
        $videoId = strtok(basename($url), '_');
        $this->setVideoId($videoId);

        return parent::__construct($url, $pattern, $renderer);
    }


    /**
     * Returns the service name (ie: "Youtube" or "Vimeo")
     * @return string
     */
    public function getServiceName()
    {
        return 'DailyMotion';
    }

    /**
     * Returns if the service has a thumbnail image
     * @return bool
     */
    public function hasThumbnail()
    {
        return true;
    }

    /**
     * Returns all thumbnails available sizes
     * @return array
     */
    public function getThumbNailSizes()
    {
        return array(
            self::THUMBNAIL_DEFAULT,
        );
    }

    /**
     * @param string $size
     * @return string
     */
    public function getThumbnail($size)
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new \RicardoFiorani\Exception\InvalidThumbnailSizeException;
        }

        return 'http://www.dailymotion.com/' . $size . '/video/' . $this->videoId;
    }

    /**
     * Returns the small thumbnail's url
     * @return string
     */
    public function getSmallThumbnail()
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT);
    }

    /**
     * Returns the medium thumbnail's url
     * @return string
     */
    public function getMediumThumbnail()
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT);
    }

    /**
     * Returns the large thumbnail's url
     * @return string
     */
    public function getLargeThumbnail()
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT);
    }

    /**
     * Returns the largest thumnbnaail's url
     * @return string
     */
    public function getLargestThumbnail()
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT);
    }

    /**
     * @param bool $autoplay
     * @return string
     */
    public function getEmbedUrl($autoplay = false)
    {
        return '//www.dailymotion.com/embed/video/' . $this->videoId . ($autoplay ? '?amp&autoplay=1' : '');
    }

    /**
     * @return bool
     */
    public function isEmbeddable()
    {
        return true;
    }
}
```
## The DailyMotionServiceAdapterFactory
```php
<?php
namespace MyVendor\ServiceAdapter\Factory;


use MyVendor\ServiceAdapter\DailyMotionServiceAdapter;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class DailyMotionServiceAdapterFactory implements \RicardoFiorani\Adapter\Factory\CallableServiceAdapterFactoryInterface
{
    /**
     * @param string $url
     * @param string $pattern
     * @param EmbedRendererInterface $renderer
     * @return VideoAdapterInterface
     */
    public function __invoke($url, $pattern, EmbedRendererInterface $renderer)
    {
        $dailyMotionServiceAdapter = new DailyMotionServiceAdapter($url, $pattern, $renderer);

        return $dailyMotionServiceAdapter;
    }
}
```

## Making things work (gluing all together)

```php
<?php
use RicardoFiorani\Detector\VideoServiceDetector;

require __DIR__ . '/vendor/autoload.php';

$vsd = new VideoServiceDetector();
//The Service Name
$serviceName = 'DailyMotion';

//The Pattern used to identify this service
//You can use multiple, but make sure your ServiceAdapter can handle it properly
$patterns = array(
    '#https?://www.dailymotion.com/video/([A-Za-z0-9]+)#s'
);

//Register the new service
$vsd->getServiceContainer()->registerService($serviceName, $patterns, "\\MyVendor\\ServiceAdapter\\Factory\\DailyMotionServiceAdapterFactory");

//This will get you an DailyMotionServiceAdapter
$video = $vsd->parse('http://www.dailymotion.com/video/x33ncwc_kittens-fight-in-tiny-boxing-ring_animals');

```
