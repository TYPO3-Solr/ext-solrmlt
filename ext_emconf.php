<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "solrmlt".
 *
 * Auto generated 10-04-2015 10:12
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Apache Solr for TYPO3 - More Like This',
	'description' => 'More Like This',
	'category' => 'plugin',
	'author' => 'Ingo Renner',
	'author_email' => 'ingo@typo3.org',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'author_company' => 'dkd Internet Service GmbH',
	'version' => '1.1.0-dev',
	'constraints' => array(
		'depends' => array(
			'solr' => '3.1.0-',
			'typo3' => '6.2.0-7.9.9',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => '',
);

?>
