<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id: ext_autoload.php $
 */

 
$extensionPath = t3lib_extMgm::extPath('in_gallery');
return array(
	'tx_ingallery_albumList' => $extensionPath . 'pi1/class.tx_ingallery_albumList.php',
	'tx_ingallery_imageList' => $extensionPath . 'pi1/class.tx_ingallery_imageList.php'
);
unset($extensionPath); 
?>
