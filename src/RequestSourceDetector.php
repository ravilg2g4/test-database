<?php

declare(strict_types=1);

namespace App;

class RequestSourceDetector
{
    public static function getRequestSource(): string
    {
        if (array_key_exists("REQUEST_URI", $_SERVER) === true) {
            return 'http';
        } else {
            return 'terminal';
        }
    }
}
