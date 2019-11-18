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
    public function __construct($pluginPath)
    {
        $this->pluginPath = $pluginPath;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Theme_Inheritance_Template_Directories_Collected' => 'themeDirectoriesCollected',
            'Theme_Compiler_Collect_Plugin_Javascript' => 'getJavascript',
            'Theme_Compiler_Collect_Plugin_Less' => 'getLessFiles',
            'Theme_Compiler_Collect_Javascript_Files_FilterResult' => 'filterJsFiles',
        ];
    }

    /**
     * @return array
     */
    public function themeDirectoriesCollected(\Enlight_Event_EventArgs $args)
    {
        $directories = $args->getReturn();

        $directories[] = $this->pluginPath . '/Resources/views';
        return $directories;
    }

    /**
     * @return ArrayCollection
     */
    public function getJavascript()
    {
        $jsDir = $this->pluginPath . '/Resources/frontend/_public/src/js/';
        $collection = new ArrayCollection([
            $jsDir . 'jquery.cookie-consent-manager.js',
            $jsDir . 'jquery.cookie-permission.js',
            $jsDir . 'jquery.csrf-protection.js',
        ]);

        return $collection;
    }

    /**
     * @return ArrayCollection
     */
    public function getLessFiles()
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

    /**
     * Insert our state-manager adjustment file right after the original state-manager file.
     *
     * @return array
     */
    public function filterJsFiles(\Enlight_Event_EventArgs $args)
    {
        $files = $args->getReturn();
        $stateManagerIndex = null;

        foreach ($files as $key => $javascriptPath) {
            if (stripos($javascriptPath, 'themes/Frontend/Responsive/frontend/_public/src/js/jquery.state-manager.js') !== false) {
                $stateManagerIndex = $key;
                break;
            }
        }

        if ($stateManagerIndex !== null) {
            array_splice($files, $stateManagerIndex + 1, 0, $this->pluginPath . '/Resources/frontend/_public/src/js/jquery.modify-state-manager.js');
        }

        return $files;
    }
}
