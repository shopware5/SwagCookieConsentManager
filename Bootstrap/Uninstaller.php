<?php

namespace SwagCookieConsentManager\Bootstrap;

use Doctrine\DBAL\Connection;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class Uninstaller
{
    /**
     * @var InstallContext
     */
    private $installContext;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(UninstallContext $installContext, Connection $connection)
    {
        $this->installContext = $installContext;
        $this->connection = $connection;
    }

    public function uninstall(): void
    {
        $this->removeShopPage();
    }

    private function removeShopPage(): void
    {
        $sql = <<<SQL
            DELETE FROM `s_cms_static`
            WHERE `description` = 'Cookie Einstellungen'
              AND `link` = 'javascript:openCookieConsentManager()';
SQL;

        $this->connection->exec($sql);
    }
}