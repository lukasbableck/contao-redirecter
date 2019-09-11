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

namespace LinkingYou\ContaoRedirecter\Hooks;

use Contao\Controller;
use Contao\Database;
use Contao\Environment;
use Contao\PageModel;

class Redirecter extends \Frontend {

    public function myGetRootPageFromUrl()
    {

        $uri = Environment::get('requestUri');

        $db = Database::getInstance();

        $result = $db->prepare("select * from tl_linkingyou_redirecter_redirects where published=1 and source_url=?")
            ->execute($uri);

        if ($result->fetchAssoc()) {
            $row = $result->row();

            // Build target url
            $url = '';
            switch ($row["destination_type"]) {
                case "internal_destination" :
                    $pageModel = PageModel::findById($row["destination_page"]);
                    if ($pageModel) {
                        $url = \Controller::generateFrontendUrl($pageModel->row());
                    }
                    break;
                case "external_destination" :
                    $url = $row["destination_url"];
                    break;
            }

            // Fix: Redirects with parameter does not works with html-encoded strings
            $url = html_entity_decode($url);

            switch ($row["type"]) {
                case "301" : // Umleitung (permanent)
                    Controller::redirect($url, 301);
                    exit;
                case "302" : // Umleitung (temporär)
                    Controller::redirect($url, 302);
                    exit;
            }
        }

    }

}