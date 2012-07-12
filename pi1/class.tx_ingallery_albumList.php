<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Grégory Copin <gcopin@inouit.com>
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

require_once(t3lib_extMgm::extPath('gc_lib').'class.tx_gclib_list.php');

/**
 * Plugin 'Library GC' for the 'gc_lib' extension.
 *
 * @author	Grégory Copin <gcopin@inouit.com>
 * @package	TYPO3
 * @subpackage tx_gclib
 */
class tx_ingallery_albumList extends tx_gclib_list { 
	var $conf;
	var $tableName;
	var $subPart;
	var $query;
	var $results;
	
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	array	$conf: The PlugIn configuration
	 * @param	string	$tableName: Name of the table in database
	 *
	 * @return	The content that is displayed on the website
	 */
	function main($conf, $tableName = '') {
	 	parent::main($conf, 'tx_ingallery_album');
	 	$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['in_gallery']);
	 	
	 	//insertion de JS à la demande
	 	if ($this->extConf['includeCSS']){
			$GLOBALS['TSFE']->additionalHeaderData['tx_ingallery_css'] = '<link rel="stylesheet" type="text/css" href="'.t3lib_extMgm::siteRelPath('in_gallery').'assets/style/inouit.gallery.image.css" media="all">';
	 	}
	 	if ($this->extConf['includeJquery']){
		 	$GLOBALS['TSFE']->additionalHeaderData['tx_ingallery_jquery'] = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('in_gallery').'assets/js/jquery-1.7.2.min.js"></script>';
		}
	 	if ($this->extConf['includeFancyBox']){
		 	$GLOBALS['TSFE']->additionalHeaderData['tx_ingallery_jquery_fancybox_js'] = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('in_gallery').'assets/jquery.fancybox/fancybox/jquery.fancybox-1.3.4.js"></script>';
			$GLOBALS['TSFE']->additionalHeaderData['tx_ingallery_jquery_fancybox_css'] = '<link rel="stylesheet" type="text/css" href="'.t3lib_extMgm::siteRelPath('in_gallery').'assets/jquery.fancybox/fancybox/jquery.fancybox-1.3.4.css" media="all">';
		}

		$GLOBALS['TSFE']->additionalHeaderData['tx_ingallery_launcher'] .= "<script type=\"text/javascript\">
				jQuery(document).ready(function() {
							jQuery('.openAlbumOnFancyBox').fancybox();
				});
		</script>";	 	
	 	
	 	$GLOBALS['TSFE']->additionalHeaderData['tx_ingallery_css'] = '<link rel="stylesheet" type="text/css" href="'.t3lib_extMgm::siteRelPath('in_gallery').'assets/style/inouit.gallery.image.css" media="all">';
	 	 
	 	return $this->render($this->config['templateFile'], 'TEMPLATE_ALBUM',  $this->conf['displayAlbum.'], $this->results);
	}	
 }


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/in_gallery/class.tx_ingallery_albumList.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/in_gallery/class.tx_ingallery_albumList.php']);
}