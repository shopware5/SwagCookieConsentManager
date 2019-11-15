<?php

namespace SwagCookieConsentManager\Bootstrap;

use Doctrine\DBAL\Connection;
use Shopware\Components\Plugin\Context\InstallContext;

class Installer
{
    /**
     * @var InstallContext
     */
    private $installContext;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(InstallContext $installContext, Connection $connection)
    {
        $this->installContext = $installContext;
        $this->connection = $connection;
    }

    public function install()
    {
        $this->installShopPage();
    }

    private function installShopPage()
    {
        $this->connection->exec(
            file_get_contents(__DIR__ . '/assets/shop_pages.sql')
        );
    }
}