<?php
$extensionPath = t3lib_extMgm::extPath('solrmlt');
return array(

	'tx_solrmlt_classloader' => $extensionPath . 'classes/class.tx_solrmlt_classloader.php',

	'tx_solr_morelikethisquery' => $extensionPath . 'classes/class.tx_solr_morelikethisquery.php',

	'tx_solr_pi_mlt' => $extensionPath . 'pi_mlt/class.tx_solr_pi_mlt.php',

);
?>