<?php

namespace App\Exceptions;

class BadRequestException extends BaseException
{
    public function __construct($content)
    {
        parent::__construct(400, $content);
    }
}
