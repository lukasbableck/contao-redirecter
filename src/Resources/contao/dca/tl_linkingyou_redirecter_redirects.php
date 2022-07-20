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
 * Load tl_content language file
 */
System::loadLanguageFile('tl_linkingyou_redirecter_redirects');

$GLOBALS['TL_DCA']['tl_linkingyou_redirecter_redirects'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('sorting'),
            'panelLayout'             => 'filter;sort,search,limit'
        ),
        'label' => array(
            'fields' => array('source_url', 'destination_url', 'counter'),
            'format' => '%s -> %s (Aufrufe: %s)',
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'Wirklich löschen?\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => [
                'label'                 => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['toggle'],
                'attributes'            => 'onclick="Backend.getScrollOffset();"',
                'haste_ajax_operation'  => [
                    'field'     => 'published',
                    'options'    => [
                        [
                            'value'     => '',
                            'icon'      => 'invisible.svg'
                        ],
                        [
                            'value'     => '1',
                            'icon'      => 'visible.svg'
                        ]
                    ]
                ]
            ],
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'              => array('destination_type'),
        'default'                     => '{title_legend},source_url,destination_type,type,published,counter',
        'external_destination'                     => '{title_legend},source_url,destination_type,destination_url,type,published,counter',
        'internal_destination'                     => '{title_legend},source_url,destination_type,destination_page,type,published,counter'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'sorting' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['MSC']['sorting'],
            'sorting'                 => true,
            'flag'                    => 11,
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'source_url' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['source_url'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array(
                'mandatory'=>true,
                'maxlength'=>255,
                'rgxp' => 'redirectsource',
                'unique' => true
            ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'destination_type' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['destination_type'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array(
                'external_destination' => 'Externe URL',
                'internal_destination' => 'Interne Seite'
            ),
            'eval' => array(
                'submitOnChange' => true,
                'mandatory' => true,
                'includeBlankOption' => true
            ),
            'sql'                     => "varchar(128) NOT NULL default ''"
        ),
        'destination_page' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['destination_page'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'pageTree',
            'sql'                     => "varchar(255) NOT NULL default ''",
            'foreignKey'              => 'tl_page.title',
            'eval'                    => array('mandatory'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr'),
            //'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'hasOne', 'load'=>'eager')
        ),
        'destination_url' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['destination_url'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array(
                'mandatory'=>true,
                'maxlength'=>255,
                'rgxp' => 'url'
            ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'type' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['type'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array(
                '301' => '301 Permanente Weiterleitung',
                '302' => '302 Temporäre Weiterleitung'
            ),
            'sql'                     => "varchar(128) NOT NULL default ''",
            'default' => '302'
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['published'],
            'exclude'                 => true,
            'filter'                  => true,
            'flag'                    => 2,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'counter' => array(
            'label'                     => &$GLOBALS['TL_LANG']['tl_linkingyou_redirecter_redirects']['counter'],
            'inputType'                 => 'text',
            'sql'                       => "int(10) unsigned NOT NULL default '0'"
        )
    )
);
