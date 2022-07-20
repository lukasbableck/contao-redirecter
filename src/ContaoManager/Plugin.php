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

namespace LinkingYou\ContaoRedirecter\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create('LinkingYou\ContaoRedirecter\ContaoRedirecterBundle')
            ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}