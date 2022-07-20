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

namespace LinkingYou\ContaoRedirecter\Utils;

use Contao\Widget;

class Regex {

    public function addRedirectSourceRegexp($strRegexp, $varValue, Widget $objWidget): bool
    {
        if ($strRegexp == 'redirectsource')
        {
            if (!preg_match('/^\/.*$/', $varValue))
            {
                $objWidget->addError('Field ' . $objWidget->label . ' should be starts with a Slash.');
            }

            return true;
        }

        return false;
    }

}