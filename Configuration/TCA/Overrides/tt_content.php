<?php

// adding the More Like This plugin
$pluginCode = 'solr_pi_mlt';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    array(
        'LLL:EXT:solrmlt/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi_mlt',
        $pluginCode
    ),
    'list_type',
    'solrmlt'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginCode] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginCode] = 'pi_flexform';

// add flexform to pi_mlt
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginCode,
    'FILE:EXT:solrmlt/Configuration/FlexForms/MoreLikeThis.xml'
);
