<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:in_gallery/class.tx_ingallery_action_tcemainprocdm.php:tx_ingallery_action_tcemainprocdm';
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:in_gallery/class.tx_ingallery_action_tcemainproccm.php:tx_ingallery_action_tcemainproccm';

$_EXTCONF = unserialize($_EXTCONF);	// unserializing the configuration so we can use it here:

?>
