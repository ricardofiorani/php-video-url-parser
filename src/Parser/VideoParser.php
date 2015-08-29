<?php

namespace RicardoFiorani\Parser;

/**
 * @author Ricardo Fiorani
 */
class VideoParser
{

    private $videoId;
    private $tipo;
    private $thumb;
    private $src;
    private $srcAutoplay;
    private $matchs = array();
    private $embeed;
    private $entrada;

    public function __construct($video_string, $tamanho = array(555, 643))
    {
        if (empty($video_string)) {
            return null;
        }
        if ((strpos($video_string, 'youtube') == false) and (strpos($video_string, 'youtu.be') == false)) {
            // Caso seja VIMEO
            if (preg_match('#(http://vimeo.com)/([0-9]+)#i', $video_string, $match)) {
                $this->videoId = $match[2];
                if (empty($this->videoId)) {
                    $this->videoId = $match[1];
                }
                $imgid = $this->videoId;
                $hash = unserialize(@file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
                $this->thumb = $hash[0]['thumbnail_large'];
                //Com autoplay
                //$this->src= 'http://player.vimeo.com/video/' . $imgid . '?byline=0&amp;portrait=0&amp;color=ff9933&amp;autoplay=1';
                $this->src = 'http://player.vimeo.com/video/' . $imgid . '?byline=0&amp;portrait=0&amp';
                $this->srcAutoplay = 'http://player.vimeo.com/video/' . $imgid . '?byline=0&amp;portrait=0&amp&autoplay=1';
            }
            $this->tipo = 'vimeo';
        } else {
            // Caso realmente seja youtube
            if (preg_match('#(?:<\>]+href=\")?(?:http://)?((?:[a-zA-Z]{1,4}\.)?youtube.com/(?:watch)?\?v=(.{11}?))[^"]*(?:\"[^\<\>]*>)?([^\<\>]*)(?:)?#',
                $video_string, $match)) {
                $this->videoId = $match[2];
                if (empty($this->videoId)) {
                    $this->videoId = $match[1];
                }
                $this->tipo = 'youtube';
                $this->thumb = 'http://img.youtube.com/vi/' . $this->videoId . '/0.jpg';
                $this->src = 'http://www.youtube.com/embed/' . $this->videoId;
                $this->srcAutoplay = 'http://www.youtube.com/embed/' . $this->videoId . '?amp&autoplay=1';
            } else {
                if (preg_match('%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                    $video_string, $match)) {
                    $this->videoId = $match[1];
                    if (empty($this->videoId)) {
                        $this->videoId = $match[2];
                    }
                    $this->tipo = 'youtube';
                    $this->thumb = 'http://img.youtube.com/vi/' . $this->videoId . '/0.jpg';
                    $this->src = 'http://www.youtube.com/embed/' . $this->videoId;
                    $this->srcAutoplay = 'http://www.youtube.com/embed/' . $this->videoId . '?amp&autoplay=1';
                }
            }
        }
        $this->matchs = $match;
        $this->embeed = '<iframe width="' . $tamanho[0] . '" height="' . $tamanho[1] . '" src="' . addslashes($this->src) . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
        $this->entrada = $video_string;
    }

    function getVideoId()
    {
        return $this->videoId;
    }

    function getTipo()
    {
        return $this->tipo;
    }

    function getThumb()
    {
        return $this->thumb;
    }

    function getSrc()
    {
        return $this->src;
    }

    function getSrcAutoplay()
    {
        return $this->srcAutoplay;
    }

    function getMatchs()
    {
        return $this->matchs;
    }

    function getEmbeed()
    {
        return $this->embeed;
    }

    function getEntrada()
    {
        return $this->entrada;
    }

}
