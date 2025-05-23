<?php

use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

// add meta palette to sysfolders
$GLOBALS['TCA']['pages']['types'][PageRepository::DOKTYPE_SYSFOLDER]['showitem'] = str_replace(
    '--palette--;LLL:EXT:cms/locallang_tca.xlf:pages.palettes.visibility;hiddenonly,',
    '--palette--;LLL:EXT:cms/locallang_tca.xlf:pages.palettes.visibility;hiddenonly, ' .
    '--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.metadata, ' .
    '--palette--;LLL:EXT:cms/locallang_tca.xlf:pages.palettes.editorial;editorial,',
    $GLOBALS['TCA']['pages']['types'][PageRepository::DOKTYPE_SYSFOLDER]['showitem']
);

$tca = [
    'tx_in2responsible_check' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:in2responsible/Resources/Private/Language/locallang_db.xlf:in2responsible.check',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('pages', $tca);
ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'tx_in2responsible_check;;;;1-1-1',
    '',
    'after:author_email'
);
