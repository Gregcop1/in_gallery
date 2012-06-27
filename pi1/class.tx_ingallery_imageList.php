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
 class tx_ingallery_imageList extends tx_gclib_list { 
	var $prefixId      = 'tx_ingallery_pi1';		// Same as class name
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
	 	 parent::main($conf, 'tx_ingallery_image');
	 	 
	 	 //insertion de JS à la demande
	 	 $GLOBALS['TSFE']->additionalHeaderData['tx_ingallery'] .= '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('in_gallery').'assets/js/jquery-1.7.2.min.js"></script>';
	 	 $GLOBALS['TSFE']->additionalHeaderData['tx_ingallery'] .= '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('in_gallery').'assets/js/inouit.gallery.image.js"></script>';
	 	 $GLOBALS['TSFE']->additionalHeaderData['tx_ingallery'] .= '<link rel="stylesheet" type="text/css" href="'.t3lib_extMgm::siteRelPath('in_gallery').'assets/style/fade.css" media="all">';

	 	 $this->results = $this->execQuery( $this->query );
	 	 return $this->render($this->config['templateFile'], 'TEMPLATE_IMAGE',  $this->conf['displayImage.'], $this->results);
	 }	

	 /**
	  * Include query part to link album and find the selected or last album if necessary
	  */
	 function initFilterQueryParts(){
	 	$this->query['FROM'] .= ' LEFT JOIN tx_ingallery_album on ( tx_ingallery_album.uid = '.$this->tableName.'.tx_ingallery_album_uid )';
		
		//if an album is selected, take this one, else, take the last enabled album (order by sorting)
		if($this->piVars['album']){
			$this->query['WHERE'] .= (	$this->config['pidList'] ? ' AND tx_ingallery_album.pid in ('.implode(',', $this->getRecursivePid( $this->config['pidList'], $this->config['recursive'] )).')' : '')
									. $this->cObj->enableFields('tx_ingallery_album')
									. ' AND tx_ingallery_image.tx_ingallery_album_uid="'.$this->piVars['album'].'"';
		}else {
			$this->query['WHERE'] .= ' AND tx_ingallery_album_uid = ('
										. 'SELECT tx_ingallery_album.uid'
										. ' FROM tx_ingallery_album'
										. ' WHERE 1 '
										. $this->cObj->enableFields('tx_ingallery_album')
										. ' ORDER BY tx_ingallery_album.sorting DESC'
										. ' LIMIT 1'
									.')';
		}
	 }
 }


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/in_gallery/class.tx_ingallery_imageList.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/in_gallery/class.tx_ingallery_imageList.php']);
}