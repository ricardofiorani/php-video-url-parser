# PHP Video URL Parser
PHP Video URL Parser receives an video service URL (Like Youtube or VIMEO) and returns an object containing some video info
such as Thumnail's, Video Title, Video Description.

## Installation

Install the latest version with

```bash
$ composer require ricardofiorani/php-video-url-parser
```

## Basic Usage

```php
<?php
use RicardoFiorani\Detector\VideoServiceDetector;

require __DIR__ . '/vendor/autoload.php';

$vsd = new VideoServiceDetector();

//Detects wich service the url belongs to and returns the service's implementation
//of RicardoFiorani\Adapter\VideoAdapterInterface
$video = $vsd->parse('https://www.youtube.com/watch?v=PkOcm_XaWrw');

//Checks if service provides embeddable videos (most services does)
if ($video->isEmbeddable()) {
    //Will echo the embed html element with the size 200x200
    echo $video->getEmbedCode(200, 200);

    //Returns the embed html element with the size 1920x1080 and autoplay enable
    echo $video->getEmbedCode(1920, 1080, true);
}

//If you don't want to check if service provides embeddable videos you can try/catch
try {
    echo $video->getEmbedUrl();
} catch (\RicardoFiorani\Exception\NotEmbeddableException $e) {
    die(sprintf("The URL %s service does not provide embeddable videos.", $video->getRawUrl()));
}

//Gets URL of the smallest thumbnail size available
echo $video->getSmallThumbnail();

//Gets URL of the largest thumbnail size available
//Note some services (such as Youtube) does not provide the largest thumbnail for some low quality videos (like the one used in this example)
echo $video->getLargestThumbnail();
```

## Registering your own service video (it's easy !)
If you want to register an implementation of some service your class just needs to implement the "RicardoFiorani\Adapter\VideoAdapterInterface" or extend the RicardoFiorani\Adapter\AbstractServiceAdapter

A Fully functional example can be found [Here](https://github.com/ricardofiorani/php-video-url-parser/tree/master/example/RegisteringANewService.md).

PS: If you've made your awesome implementation of some well known service, feel free to send a Pull Request. All contributions are welcome :)

## Using your own framework's template engine
In this project I've used a simple renderer (wich just does an echo of an iframe) but you can use your own implementation. It must follow the RicardoFiorani\Renderer\EmbedRendererInterface and just like that. 

Here's an example:
### My Example Renderer Class
```php
namespace MyVendor\MyRenderer;


class MyOwnRenderer implements \RicardoFiorani\Renderer\EmbedRendererInterface
{

    /**
     * @param string $embedUrl
     * @param integer $height
     * @param integer $width
     * @return string
     */
    public function render($embedUrl, $height, $width)
    {
        //Just for example porpoises
        return "Hell yeah baby, you've rendered: ".addslashes($embedUrl);
        
        //A functional example would be like
        //return '<iframe width="' . $width . '" height="' . $height . '" src="' . addslashes($embedUrl) . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
}
```
### My Example Renderer Factory Class
```php
namespace MyVendor\MyRenderer\Factory;

class MyOwnRendererFactory implements RendererFactoryInterface
{
    /**
     * @return EmbedRendererInterface
     */
    public function __invoke()
    {
        $renderer = new MyOwnRenderer();
    }
}
```
### Registering my renderer 

```php
<?php
use RicardoFiorani\Detector\VideoServiceDetector;

require __DIR__ . '/vendor/autoload.php';

$vsd = new VideoServiceDetector();

//This is where the magic os done
$vsd->setRenderer('MyOwnRenderer', 'MyVendor\\MyRenderer\\Factory\\MyOwnRendererFactory');

$video = $vsd->parse('https://www.youtube.com/watch?v=PkOcm_XaWrw');

//This will output "Hell yeah baby, you've rendered: http://www.youtube.com/embed/PkOcm_XaWrw"
echo $video->getEmbedCode(500,500);

```

### Currently Suported Services
* Youtube
* Vimeo

# TODO List goals for release 1.0:

* Fix the Exceptions Messages
* Create PHPUnit Tests
* Add more Services