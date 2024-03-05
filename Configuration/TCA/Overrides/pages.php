<?php

defined('TYPO3') || die();

// add meta palette to page type 254 = sysfolder
$GLOBALS['TCA']['pages']['types']['254']['showitem'] =
    str_replace(
        '--palette--;LLL:EXT:cms/locallang_tca.xlf:pages.palettes.visibility;hiddenonly,',
        '--palette--;LLL:EXT:cms/locallang_tca.xlf:pages.palettes.visibility;hiddenonly, ' .
        '--div--;LLL:EXT:cms/locallang_tca.xlf:pages.tabs.metadata, ' .
        '--palette--;LLL:EXT:cms/locallang_tca.xlf:pages.palettes.editorial;editorial,',
        $GLOBALS['TCA']['pages']['types']['254']['showitem']
    );

$tca = [
    'tx_in2responsible_check' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:in2responsible/Resources/Private/Language/locallang_db.xlf:in2responsible.check',
        'config' => [
            'type' => 'check',
            'default' => '1',
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tca, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'tx_in2responsible_check;;;;1-1-1',
    '',
    'after:author_email'
);
