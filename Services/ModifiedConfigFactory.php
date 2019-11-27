<?php

namespace SwagCookieConsentManager\Services;

class ModifiedConfigFactory
{
    public function factory()
    {
        $arguments = func_get_args();

        list($cache, $db, $config) = $arguments;

        if (!$db) {
            return null;
        }

        if (isset($arguments[3]) && $arguments[3] instanceof \Shopware\Components\ShopwareReleaseStruct) {
            $config['release'] = $arguments[3];
        }

        if (!isset($config['cache'])) {
            $config['cache'] = $cache;
        }

        $config['db'] = $db;

        return new ConfigModified($config);
    }
}