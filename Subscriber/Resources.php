<?php

namespace SwagCookieConsentManager\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use Shopware\Components\Theme\LessDefinition;

class Resources implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginPath;

    /**
     * @param string $pluginPath
     */
    public function __construct(string $pluginPath)
    {
        $this->pluginPath = $pluginPath;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'Theme_Inheritance_Template_Directories_Collected' => 'themeDirectoriesCollected',
            'Theme_Compiler_Collect_Plugin_Javascript' => 'getJavascript',
            'Theme_Compiler_Collect_Plugin_Less' => 'getLessFiles'
        ];
    }

    public function themeDirectoriesCollected(\Enlight_Event_EventArgs $args): array
    {
        $directories = $args->getReturn();

        $directories[] = $this->pluginPath . '/Resources/views';
        return $directories;
    }

    public function getJavascript(): ArrayCollection
    {
        $jsDir = $this->pluginPath . '/Resources/frontend/_public/src/js/';
        $collection = new ArrayCollection([
            $jsDir . 'jquery.cookie-consent-manager.js',
        ]);

        return $collection;
    }

    public function getLessFiles(): ArrayCollection
    {
        $lessDir = $this->pluginPath . '/Resources/frontend/_public/src/less/';

        $less = new LessDefinition(
            [],
            [
                $lessDir . 'all.less',
            ]
        );

        return new ArrayCollection([$less]);
    }
}
