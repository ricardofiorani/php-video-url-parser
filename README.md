# PHP Video URL Parser
[![Build Status](https://api.travis-ci.org/ricardofiorani/php-video-url-parser.svg?branch=master)](http://travis-ci.org/ricardofiorani/php-video-url-parser)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.3-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/ricardofiorani/php-video-url-parser/license.png)](https://packagist.org/packages/ricardofiorani/php-video-url-parser)
[![Total Downloads](https://poser.pugx.org/ricardofiorani/php-video-url-parser/d/total.png)](https://packagist.org/packages/ricardofiorani/php-video-url-parser)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--4-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ricardofiorani/php-video-url-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ricardofiorani/php-video-url-parser/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ricardofiorani/php-video-url-parser/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ricardofiorani/php-video-url-parser/?branch=master)

PHP Video URL Parser is a parser that detects a given video url and returns an object containing information like the video's embed code, title, description, thumbnail and other information that the service's API may give.

## Installation

Install the latest version with

```bash
$ composer require ricardofiorani/php-video-url-parser
```

## Requirements

* PHP 5.3
* cURL (Or at least file_get_contents() enabled if you want to use it with Vimeo, otherwise it's not required)

## Basic Usage

```php
<?php
use RicardoFiorani\Matcher\VideoServiceMatcher;

require __DIR__ . '/vendor/autoload.php';

$vsm = new VideoServiceMatcher();

//Detects which service the url belongs to and returns the service's implementation
//of RicardoFiorani\Adapter\VideoAdapterInterface
$video = $vsm->parse('https://www.youtube.com/watch?v=PkOcm_XaWrw');

//Checks if service provides embeddable videos (most services does)
if ($video->isEmbeddable()) {
    //Will echo the embed html element with the size 200x200
    echo $video->getEmbedCode(200, 200);

    //Returns the embed html element with the size 1920x1080 and autoplay enabled
    echo $video->getEmbedCode(1920, 1080, true);
    
    //Returns the embed html element with the size 1920x1080, autoplay enabled and force the URL schema to be https.
    echo $video->getEmbedCode(1920, 1080, true, true);
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

A Fully functional example can be found [Here](https://github.com/ricardofiorani/php-video-url-parser/tree/master/documentation/RegisteringANewService.md).

PS: If you've made your awesome implementation of some well known service, feel free to send a Pull Request. All contributions are welcome :)

## Using your own framework's template engine
A Fully functional example can be found [Here](https://github.com/ricardofiorani/php-video-url-parser/tree/master/documentation/IntegratingYourOwnRenderer.md).


## Currently Suported Services
* Youtube
* Vimeo
* Dailymotion
* Facebook Videos

## Currently Supported PHP Versions
* PHP 5.3
* PHP 5.4
* PHP 5.5
* PHP 5.6
* PHP 7.0
* PHP 7.1
* PHP 7.2

> Please note that lib is not passing tests on HHVM, therefore, we can't guarantee it will work properly. Please use it on your own risk.

