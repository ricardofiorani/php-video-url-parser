<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:34
 */

/**
 * Array with Available Services
 */
return array(
    '#(https?://vimeo.com)/([0-9]+)#i' => 'RicardoFiorani\\Container\\Factory\\VimeoVideoContainerFactory',
);