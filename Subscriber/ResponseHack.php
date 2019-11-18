<?php

namespace SwagCookieConsentManager\Subscriber;

use Enlight\Event\SubscriberInterface;
use SwagCookieConsentManager\Services\EnlightResponseCookieModified;

class ResponseHack implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Front::setResponse::before' => 'injectCustomResponse'
        ];
    }

    public function injectCustomResponse(\Enlight_Hook_HookArgs $args)
    {
        $responseClass = $args->getArgs()[0];

        if ($responseClass === 'Enlight_Controller_Response_ResponseHttp') {
            $responseClass = EnlightResponseCookieModified::class;
        }

        $args->set('response', $responseClass);
    }
}