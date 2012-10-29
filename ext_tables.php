<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}


   # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- #


	// TypoScript
t3lib_extMgm::addStaticFile($_EXTKEY, 'static/solrmlt/', 'Apache Solr - More Like This');

   # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- #

	// adding the More Like This plugin
t3lib_extMgm::addPlugin(
	array(
		'LLL:EXT:solrmlt/locallang_db.xml:tt_content.list_type_pi_mlt',
		'solr_pi_mlt'
	),
	'list_type'
);
$TCA['tt_content']['types']['list']['subtypes_excludelist']['solr_pi_mlt'] = 'layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist']['solr_pi_mlt'] = 'pi_flexform';

	// add flexform to pi_mlt
t3lib_extMgm::addPiFlexFormValue('solr_pi_mlt', 'FILE:EXT:solrmlt/flexforms/pi_mlt.xml');


?>