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
	 	 
	 	 $this->results = $this->execQuery( $this->query );
	 	 return $this->render($this->config['templateFile'], 'TEMPLATE_IMAGE',  $this->conf['displayImage.'], $this->results);
	 }	

	 /**
	  * Include query part to link album and find the last album if necessary
	  */
	 function initFilterQueryParts(){
	 	$this->query['FROM'] .= ' LEFT JOIN tx_ingallery_album on ( tx_ingallery_album.uid = '.$this->tableName.'.tx_ingallery_album_uid )';
		$this->query['WHERE'] .= (	$this->config['pidList'] ? ' AND tx_ingallery_album.pid in ('.implode(',', $this->getRecursivePid( $this->config['pidList'], $this->config['recursive'] )).')' : '')
					. $this->cObj->enableFields('tx_ingallery_album');
//		$this->query['ORDER BY'] = $this->tableName.'.uid';
		//$this->query['GROUP BY'] .= ($this->config['groupBy'] ? $this->config['groupBy'] : '');
		//$this->query['ORDER BY'] .= ($this->config['orderBy'] ? $this->config['orderBy'] : '');
		//$this->query['LIMIT'] .= ($this->config['limit'] ? $this->config['limit'] : '');
	 }
 }


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/in_gallery/class.tx_ingallery_imageList.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/in_gallery/class.tx_ingallery_imageList.php']);
}