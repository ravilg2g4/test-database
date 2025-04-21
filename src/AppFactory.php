<?php

declare(strict_types=1);

namespace App;

use App\Repositories\Http\AppHttp;
use App\Repositories\Terminal\AppTerminal;

class AppFactory
{
    public static function getApp(): ?AppInterface
    {
        if (array_key_exists("SERVER_PROTOCOL", $_SERVER) === true) {
            return new AppHttp();
        } elseif (array_key_exists("REQUEST_URI", $_SERVER) === false) {
            return new AppTerminal();
        } else {
            return null;
        }
    }
}
