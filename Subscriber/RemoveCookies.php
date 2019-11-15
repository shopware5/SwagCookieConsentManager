<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace SwagCookieConsentManager\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Request_RequestHttp as Request;
use Shopware_Components_Config as Config;
use Enlight_Controller_Response_ResponseHttp as Response;
use SwagCookieConsentManager\Bundle\CookieBundle\CookieGroupCollection;
use SwagCookieConsentManager\Bundle\CookieBundle\Services\CookieHandler;
use SwagCookieConsentManager\Bundle\CookieBundle\Services\CookieHandlerInterface;
use Symfony\Component\HttpFoundation\Cookie;

class RemoveCookies implements SubscriberInterface
{
    const COOKIE_MODE_NOTICE = 0;
    const COOKIE_MODE_TECHNICAL = 1;
    const COOKIE_MODE_ALL = 2;

    /**
     * @var bool
     */
    private $cookieRemovalActive;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CookieHandlerInterface
     */
    private $cookieHandler;

    public function __construct(Config $config, CookieHandlerInterface $cookieHandler)
    {
        $this->cookieRemovalActive = $config->get('cookie_note_mode') && $config->get('show_cookie_note');
        $this->config = $config;
        $this->cookieHandler = $cookieHandler;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Frontend' => 'onPostDispatch',
            'Enlight_Controller_Action_PostDispatch_Widgets' => 'onPostDispatch',
        ];
    }

    public function onPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        if (!$this->cookieRemovalActive) {
            return;
        }

        $controller = $args->getSubject();

        $allowCookie = (int) $controller->Request()->getCookie('allowCookie');

        $cookieNoteMode = (int) $this->config->get('cookie_note_mode');

        if ($cookieNoteMode === self::COOKIE_MODE_ALL) {
            if ($allowCookie === 1) {
                return;
            }

            header_remove('Set-Cookie');

            $this->removeAllCookies($controller->Request(), $controller->Response());

            return;
        }

        if ($cookieNoteMode === self::COOKIE_MODE_TECHNICAL) {
            $controller->View()->assign(
                'cookieGroups',
                $this->convertToArray($this->cookieHandler->getCookies())
            );

            if ($allowCookie === 1) {
                return;
            }

            $this->removeCookiesFromPreferences($controller->Request(), $controller->Response());
        }
    }

    private function removeCookiesFromPreferences(Request $request, Response $response)
    {
        $preferences = $request->getCookie(CookieHandler::PREFERENCES_COOKIE_NAME);

        if ($preferences === null) {
            $this->removeAllCookies($request, $response);

            return;
        }

        $preferences = json_decode($preferences, true);

        $this->removeCookies($request, $response, function ($cookieName) use ($preferences) {
            return $this->cookieHandler->isCookieAllowedByPreferences($cookieName, $preferences);
        });
    }

    private function removeAllCookies(Request $request, Response $response)
    {
        $technicallyRequiredCookies = $this->cookieHandler->getTechnicallyRequiredCookies();

        $this->removeCookies($request, $response, static function ($cookieKey) use ($technicallyRequiredCookies) {
            return $technicallyRequiredCookies->hasCookieWithName($cookieKey);
        });
    }

    private function removeCookies(Request $request, Response $response, callable $validationFunction)
    {
        $requestCookies = $request->getCookie();
        $cookieBasePath = $request->getBasePath();

        $cookiePath = $cookieBasePath . '/';
        $currentPath = $cookieBasePath . $request->getPathInfo();
        $currentPathWithoutSlash = trim($currentPath, '/');

        foreach ($response->getCookies() as $responseCookie) {
            if (!$validationFunction($responseCookie['name'])) {
                if (array_key_exists($responseCookie['name'], $requestCookies)) {
                    continue;
                }

                $response->removeCookie($responseCookie['name']);
                $response->removeCookie($responseCookie['name'], $cookieBasePath);
                $response->removeCookie($responseCookie['name'], $cookiePath);
                $response->removeCookie($responseCookie['name'], $currentPath);
                $response->removeCookie($responseCookie['name'], $currentPathWithoutSlash);
            }
        }

        foreach ($requestCookies as $cookieKey => $cookieName) {
            if (!$validationFunction($cookieKey)) {
                $response->setCookie($cookieKey, null, 0);
                $response->setCookie($cookieKey, null, 0, $cookieBasePath);
                $response->setCookie($cookieKey, null, 0, $cookiePath);
                $response->setCookie($cookieKey, null, 0, $currentPath);
                $response->setCookie($cookieKey, null, 0, $currentPathWithoutSlash);
            }
        }
    }

    /**
     * @return array
     */
    private function convertToArray(CookieGroupCollection $cookieGroupCollection)
    {
        return json_decode(json_encode($cookieGroupCollection), true);
    }
}
