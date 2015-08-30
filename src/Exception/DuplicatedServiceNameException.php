<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 23:28
 */

namespace RicardoFiorani\Exception;


use Exception;

class DuplicatedServiceNameException extends Exception
{

    /**
     * DuplicatedServiceNameException constructor.
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($code = 0, Exception $previous = null)
    {
        return parent::__construct("", $code, $previous);
    }
}
