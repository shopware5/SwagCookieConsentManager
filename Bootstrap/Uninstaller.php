<?php declare(strict_types=1);

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

    /**
     * @var bool
     */
    private $isShopware563;

    public function __construct(UninstallContext $installContext, Connection $connection, bool $isShopware563)
    {
        $this->installContext = $installContext;
        $this->connection = $connection;
        $this->isShopware563 = $isShopware563;
    }

    public function uninstall(): void
    {
        if ($this->isShopware563) {
            return;
        }

        $this->removeShopPage();
    }

    private function removeShopPage(): void
    {
        $sql = <<<SQL
            DELETE FROM `s_cms_static`
            WHERE `link` = 'javascript:openCookieConsentManager()';
SQL;

        $this->connection->exec($sql);
    }
}