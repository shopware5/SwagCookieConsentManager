<?php

namespace SwagCookieConsentManager\Services;

class EnlightResponseCookieModified extends \Enlight_Controller_Response_ResponseHttp
{
    /**
     * @param string $name
     */
    public function removeCookie($name, $path = '/')
    {
        unset($this->_cookies[$name . $path]);
    }
}