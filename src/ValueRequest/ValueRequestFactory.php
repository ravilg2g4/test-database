<?php

namespace App\ValueRequest;

class ValueRequestFactory
{
    public function getValueRequest(): ValueRequestInterface
    {
        if (array_key_exists("REQUEST_URI", $_SERVER) === false) {
            $valueRequest = new TerminalValueRequest();
        } else {
            $valueRequest = new HttpValueRequest();
        }
        return $valueRequest;
    }
}
