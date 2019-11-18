<?php

namespace SwagCookieConsentManager\Services;

class ConfigModified extends \Shopware_Components_Config
{
    const CONFIG_NAMES = ['show_cookie_note', 'cookie_note_mode', 'cookie_show_button'];
    const CONFIG_PREFIX = 'swag_cookie';

    public function offsetGet($name)
    {
        if (in_array($name, self::CONFIG_NAMES, true)) {
            $name = self::CONFIG_PREFIX . '.' . $name;
        }

        return parent::offsetGet($name);
    }
}