<?php

defined('TYPO3') || die();

/**
 * Render not in backend.
 * Todo: Can be removed once TYPO3 11 support is dropped
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawHeaderHook'][] =
    \In2code\In2responsible\Hook\ModuleHookDeprecated::class . '->moduleBodyPostProcess';
