<?php

namespace Simp\Router\Router;

class NotFoundException extends \Exception
{
    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}