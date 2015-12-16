<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}


   # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- #


// TypoScript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript/MoreLikeThis/',
    'Apache Solr - More Like This'
);

   # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- #

// adding the More Like This plugin
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    array(
        'LLL:EXT:solrmlt/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi_mlt',
        'solr_pi_mlt'
    ),
    'list_type'
);
$TCA['tt_content']['types']['list']['subtypes_excludelist']['solr_pi_mlt'] = 'layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist']['solr_pi_mlt'] = 'pi_flexform';

// add flexform to pi_mlt
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'solr_pi_mlt',
    'FILE:EXT:solrmlt/Configuration/FlexForms/MoreLikeThis.xml'
);
