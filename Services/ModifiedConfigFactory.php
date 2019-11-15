<?php

namespace SwagCookieConsentManager\Services;

use Doctrine\DBAL\Connection;
use Shopware\Components\DependencyInjection\Bridge\Config;
use Shopware\Components\ShopwareReleaseStruct;
use Zend_Cache_Core;

class ModifiedConfigFactory extends Config
{
    public function factory(\Zend_Cache_Core $cache, \Enlight_Components_Db_Adapter_Pdo_Mysql $db = null, $config = [], ShopwareReleaseStruct $release)
    {
        if (!$db) {
            return null;
        }

        if (!isset($config['cache'])) {
            $config['cache'] = $cache;
        }
        $config['db'] = $db;
        $config['release'] = $release;

        return new ConfigModified($config);
    }

}