<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 31/08/2015
 * Time: 21:54
 */

namespace RicardoFiorani\Test;

use PHPUnit_Framework_TestCase;
use RicardoFiorani\Detector\VideoServiceDetector;
use RicardoFiorani\Exception\ServiceNotAvailableException;

class ServiceDetectorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider youtubeDataProvider
     * @param $url
     * @param $expectedEmbedCode
     * @throws ServiceNotAvailableException
     */
    public function testCanParseYoutubeUrl($url, $expectedEmbedCode)
    {
        $detector = new VideoServiceDetector();
        $video = $detector->parse($url);
        $this->assertEquals($video->getEmbedUrl(), $expectedEmbedCode);
    }

    public function youtubeDataProvider()
    {

        return array(
            'Normal Youtube URL' => array(
                'https://www.youtube.com/watch?v=mWRsgZuwf_8',
                'http://www.youtube.com/embed/mWRsgZuwf_8',
            ),
            'Short Youtube URL' => array(
                'https://youtu.be/JMLBOKVfHaA',
                'http://www.youtube.com/embed/JMLBOKVfHaA',
            ),
            'Embed Youtube URL' => array(
                '<iframe width="420" height="315" src="https://www.youtube.com/embed/vwp9JkaESdg" frameborder="0" allowfullscreen></iframe>',
                'http://www.youtube.com/embed/vwp9JkaESdg',
            ),
        );
    }

    /**
     * @dataProvider vimeoDataProvider
     * @param string $url
     * @param string $expectedEmbedCode
     */
    public function testCanParseVimeoUrl($url, $expectedEmbedCode)
    {
        $detector = new VideoServiceDetector();
        $video = $detector->parse($url);
        $this->assertEquals($video->getEmbedUrl(), $expectedEmbedCode);
    }

    public function vimeoDataProvider()
    {
        return array(
            'Common Vimeo URL' => array(
                'https://vimeo.com/137781541',
                'http://player.vimeo.com/video/137781541'
            )
        );
    }
}