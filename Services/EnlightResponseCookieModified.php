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

    /**
     * {@inheritDoc}
     */
    public function setCookie($name,
        $value = null,
        $expire = 0,
        $path = '/',
        $domain = null,
        $secure = false,
        $httpOnly = false
    ) {
        $this->_cookies[$name . $path] = array(
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httpOnly' => $httpOnly
        );
        return $this;
    }

    /**
     * @return CookieArrayIterator
     */
    public function getCookies()
    {
        return new CookieArrayIterator($this->_cookies);
    }
}