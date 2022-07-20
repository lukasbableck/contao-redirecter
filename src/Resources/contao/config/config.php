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

/**
 * Register hooks
 */
$GLOBALS['TL_HOOKS']['addCustomRegexp'][] = array('LinkingYou\\ContaoRedirecter\\Utils\\Regex', 'addRedirectSourceRegexp');

/**
 * Register models
 */
$GLOBALS['TL_MODELS']['tl_linkingyou_redirecter_redirects'] = \LinkingYou\ContaoRedirecter\Model\Redirect::class;

/**
 * Add back end modules
 */

array_insert($GLOBALS['BE_MOD']['system'], 99, array
(
    'linkingyou_redirecter' => array(
        'tables' => array('tl_linkingyou_redirecter_redirects')
    )
));
