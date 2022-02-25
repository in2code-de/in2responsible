<?php

defined('TYPO3_MODE') or die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawHeaderHook'][] =
    \In2code\In2responsible\Hook\ModuleHook::class . '->moduleBodyPostProcess';
