<?php

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{

    public $content;
    public $statusCode;

    public function __construct($statusCode, $content)
    {
        $this->content = $content;

        if (gettype(($this->content) === "string")) {
            $this->content = [
                "message" => $content
            ];
        }

        $this->statusCode = $statusCode;
    }

    public function render()
    {
        return response()->json($this->content, $this->statusCode);
    }
}
