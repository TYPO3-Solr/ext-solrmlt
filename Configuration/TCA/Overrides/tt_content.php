<?php

// Register the plugins
$pluginSignature = 'solrmlt_pi_morelikethis';
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'solrmlt',
    'pi_morelikethis',
    'LLL:EXT:solrmlt/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi_mlt'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature]
    = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature]
    = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:solrmlt/Configuration/FlexForms/MoreLikeThis.xml'
);
