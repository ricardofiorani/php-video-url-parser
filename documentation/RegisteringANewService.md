# Functional Example of Registering a Dailymotion Service

## DailymotionServiceAdapter Class (The Service Adapter Itself)
```php
<?php
namespace MyVendor\ServiceAdapter;

//Your service Adapter must implement VideoAdapterInterface or Extend AbstractServiceAdapter
class DailymotionServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_DEFAULT = 'thumbnail';

    /**
     * AbstractVideoAdapter constructor.
     *
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
     * Returns the service name (ie: "Youtube" or "Vimeo").
     *
     * @return string
     */
    public function getServiceName()
    {
        return 'Dailymotion';
    }

    /**
     * Returns if the service has a thumbnail image.
     *
     * @return bool
     */
    public function hasThumbnail()
    {
        return true;
    }

    /**
     * Returns all thumbnails available sizes.
     *
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
     * @param bool $forceSecure
     *
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail($size, $forceSecure = false)
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return $this->getScheme($forceSecure) . '://www.dailymotion.com/' . $size . '/video/' . $this->videoId;
    }

    /**
     * Returns the small thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getSmallThumbnail($forceSecure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * Returns the medium thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getMediumThumbnail($forceSecure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * Returns the large thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getLargeThumbnail($forceSecure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * Returns the largest thumnbnaail's url.
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getLargestThumbnail($forceSecure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * @param bool $forceAutoplay
     * @param bool $forceSecure
     * @return string
     */
    public function getEmbedUrl($forceAutoplay = false, $forceSecure = false)
    {
        return $this->getScheme($forceSecure) . '://www.dailymotion.com/embed/video/' . $this->videoId . ($forceAutoplay ? '?amp&autoplay=1' : '');
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
## The DailymotionServiceAdapterFactory
```php
<?php
namespace MyVendor\ServiceAdapter\Factory;

use MyVendor\ServiceAdapter\DailymotionServiceAdapter;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class DailymotionServiceAdapterFactory implements \RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface
{
    /**
     * @param string $url
     * @param string $pattern
     * @param EmbedRendererInterface $renderer
     * @return VideoAdapterInterface
     */
    public function __invoke($url, $pattern, EmbedRendererInterface $renderer)
    {
        $dailyMotionServiceAdapter = new DailymotionServiceAdapter($url, $pattern, $renderer);

        return $dailyMotionServiceAdapter;
    }
}
```

## Making things work (gluing all together)

```php
<?php
use RicardoFiorani\Matcher\VideoServiceMatcher;

require __DIR__ . '/vendor/autoload.php';

$vsd = new VideoServiceMatcher();
//The Service Name
$serviceName = 'Dailymotion';

//The Pattern used to identify this service
//You can use multiple, but make sure your ServiceAdapter can handle it properly
$patterns = array(
    '#https?://www.dailymotion.com/video/([A-Za-z0-9]+)#s'
);

//Register the new service
$vsd->getServiceContainer()->registerService($serviceName, $patterns, MyVendor\ServiceAdapter\Factory\DailymotionServiceAdapterFactory::class);

//This will get you an DailymotionServiceAdapter
$video = $vsd->parse('http://www.dailymotion.com/video/x33ncwc_kittens-fight-in-tiny-boxing-ring_animals');

```
