<?php

namespace SwagCookieConsentManager\Services;

class CookieArrayIterator extends \ArrayIterator
{
    /**
     * Removes the path from the current key.
     *
     * @return string
     */
    public function key()
    {
        $key = parent::key();
        $key = str_replace($this->current()['path'], '', $key);

        return $key;
    }
}
