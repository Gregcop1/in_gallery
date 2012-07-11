<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id: ext_autoload.php $
 */

 
$extensionPath = t3lib_extMgm::extPath('in_gallery');
return array(
	'tx_ingallery_albumList' => $extensionPath . 'class.tx_ingallery_albumList.php',
);
unset($extensionPath); 
?>
