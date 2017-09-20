<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// PageTSconfig
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:in2responsible/Configuration/TypoScript/PageTSConfig.typoscript">'
);
