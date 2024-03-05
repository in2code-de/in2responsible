<?php

defined('TYPO3') || die();

// PageTSconfig
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '@import \'EXT:in2responsible/Configuration/TypoScript/PageTSConfig.typoscript\''
);
