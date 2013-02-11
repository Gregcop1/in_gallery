<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Grégory Copin <typo3@inouit.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(t3lib_extMgm::extPath('gc_lib').'class.tx_gclib.php');


/**
 * Plugin 'Category List' for the 'in_gallery' extension.
 *
 * @author	Grégory Copin <typo3@inouit.com>
 * @package	TYPO3
 * @subpackage	tx_ingallery
 */
class tx_ingallery_pi1 extends tx_gclib_base {
	var $prefixId      = 'tx_ingallery_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_ingallery_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'in_gallery';	// The extension key.

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$configuration: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$configuration) {
		parent::main($configuration);

		$content = '';
		switch($this->config['CODE']) {
			case 'imageList': {
				$content = $this->makeInstance(t3lib_extMgm::extPath($this->extKey).'pi1/class.tx_ingallery_imageList.php', 'tx_ingallery_imageList', $this->conf);
			}break;
			case 'albumList':
				$content = $this->makeInstance(t3lib_extMgm::extPath($this->extKey).'pi1/class.tx_ingallery_albumList.php', 'tx_ingallery_albumList', $this->conf);
			break;
		}

		return $this->pi_wrapInBaseClass($content);
	}
}



if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/in_gallery/pi1/class.tx_ingallery_pi1.php'])	{
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/in_gallery/pi1/class.tx_ingallery_pi1.php']);
}

?>
