<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

   # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- #

	// trigger loading of ext_autoload.php
tx_solrgeo_ClassLoader::loadClasses();

   # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- # ----- #

	// adding the More Like This plugin
t3lib_extMgm::addPItoST43(
	'solr',
	'pi_mlt/class.tx_solr_pi_mlt.php',
	'_pi_mlt',
	'list_type',
	TRUE
);

?>