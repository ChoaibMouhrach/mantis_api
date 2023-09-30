<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class NotFoundException extends BaseException
{
    public function __construct($content)
    {
        parent::__construct(404, $content);
    }
}
