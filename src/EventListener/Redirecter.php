<?php

/**
 * LinkingYou/ContaoRedirecter
 *
 * Contao URL Redirector Extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2017, Frank Müller
 * @author     Frank Müller <frank.mueller@linking-you.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 */

namespace LinkingYou\ContaoRedirecter\EventListener;

use Contao\Controller;
use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\Environment;
use Contao\Events;
use Contao\PageModel;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use LinkingYou\ContaoRedirecter\Model\Redirect;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Cache\ItemInterface;
use webignition\RobotsTxt\Record\Record;

class Redirecter implements EventSubscriberInterface {

    private $framework;

    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 0]
            ]
        ];
    }

    public function onKernelRequest(RequestEvent $event) {

        $path = $event->getRequest()->getPathInfo();

        // Check, if path defined in redirects
        $redirect = $this->getRedirectForPath($path);

        if ($redirect) {
            // store client in cache to avoid duplicate counts
            $cache = new FilesystemAdapter();
            $cacheKey = 'redirect_client_cache_' . md5( $event->getRequest()->getClientIp() . $event->getRequest()->server->get('HTTP_USER_AGENT') . $path);
            if ($cache->hasItem($cacheKey)) {
                $inCache = true;
            } else {
                $inCache = false;
                $cache->get($cacheKey, function(ItemInterface $item) {
                    $item->expiresAfter(60); // clear cache after 60 seconds
                });
            }

            // Build target url
            $url = '';
            switch ($redirect->destination_type) {
                case "internal_destination" :
                    $pageModel = PageModel::findById($redirect->destination_page);
                    if ($pageModel) {
                        $url = \Controller::generateFrontendUrl($pageModel->row());
                    }
                    break;
                case "external_destination" :
                    $url = $redirect->destination_url;
                    break;
            }

            if (!$inCache) {
                $redirect->counter++;
                $redirect->save();
            }

            // Fix: Redirects with parameter does not works with html-encoded strings
            $url = html_entity_decode($url);

            switch ($redirect->type) {
                case "301" : // Umleitung (permanent)
                    Controller::redirect($url, 301);
                    exit;
                case "302" : // Umleitung (temporär)
                    Controller::redirect($url, 302);
                    exit;
            }
        }

    }

    private function getRedirectForPath(string $uri): ?Redirect {

        $this->framework->initialize();

        $cache = new FilesystemAdapter();

        $redirect = $cache->get(md5('redirect_cache_' . $uri), function(ItemInterface $item) use ($uri) {
            $item->expiresAfter(60);
            return Redirect::findOneBy([Redirect::getTable() . '.source_url = ?', Redirect::getTable() . '.published = ?'], [$uri, 1]);
        });

        return $redirect;
    }

}