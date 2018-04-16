# Functional Example of Integrating your own renderer
In this project I've used a simple renderer (which just does an echo of an iframe) but you can use your own implementation. 
First, It must follow the RicardoFiorani\Renderer\EmbedRendererInterface interface.

Basically you need two classes:

* The Renderer Implementation
* The Renderer's Factory

The examples can be seem below:

### My Renderer Implementation Class
This is the concrete implementation on how your renderer is going to handle the embed URL to give you an embed code.
In here you can inject any dependency you might need by the constructor and add any logic you need.
Please note that it should implement the interface "\RicardoFiorani\Renderer\EmbedRendererInterface".
```php
<?php
namespace MyVendor\MyRenderer;
use \RicardoFiorani\Renderer\EmbedRendererInterface;

class MyOwnRenderer implements EmbedRendererInterface
{
    /**
     * @param string $embedUrl
     * @param integer $height
     * @param integer $width
     * @return string
     */
    public function renderVideoEmbedCode($embedUrl, $height, $width)
    {
        //Just for example porpoises
        return sprintf("Hello, I'm embedding %s", addslashes($embedUrl));
        
        //A functional example would be like
        //return '<iframe width="' . $width . '" height="' . $height . '" src="' . addslashes($embedUrl) . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
}
```
### My Renderer Implementation Factory Class
This is the Factory of your renderer, basically all it must do is to implement the interface RicardoFiorani\Renderer\Factory\RendererFactoryInterface
```php
<?php
namespace MyVendor\MyRenderer\Factory;
use RicardoFiorani\Renderer\EmbedRendererInterface;
use RicardoFiorani\Renderer\Factory\RendererFactoryInterface;

class MyOwnRendererFactory implements RendererFactoryInterface
{
    /**
     * @return EmbedRendererInterface
     */
    public function __invoke()
    {
        return new MyOwnRenderer();
    }
}
```
### Registering my renderer 

The last part is attaching your own renderer service to the VideoServiceMatcher, which can be done as the example that follows:

```php
<?php
use RicardoFiorani\Matcher\VideoServiceMatcher;

require __DIR__ . '/vendor/autoload.php';

$vsm = new VideoServiceMatcher();

//This is where you attach your own renderer to be used instead of the default one
$vsm->getServiceContainer()->setRenderer('MyOwnRenderer', MyVendor\MyRenderer\Factory\MyOwnRendererFactory::class);

$video = $vsm->parse('https://www.youtube.com/watch?v=PkOcm_XaWrw');

//This will output "Hello, I'm embedding http://www.youtube.com/embed/PkOcm_XaWrw"
echo $video->getEmbedCode(500,500);
```
