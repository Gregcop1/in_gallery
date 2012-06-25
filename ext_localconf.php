<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

$_EXTCONF = unserialize($_EXTCONF);	// unserializing the configuration so we can use it here:

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_ingallery_pi1.php', '_pi1', 'list_type', 1);
?>
