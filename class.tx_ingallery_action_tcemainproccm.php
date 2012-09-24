<?php
/***************************************************************
*  Copyright notice
*
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
*/

class tx_ingallery_action_tcemainproccm
{


    function processDatamap_postProcessFieldArray ($status, $table, $id, &$fieldArray, &$reference){
        global $FILEMOUNTS, $BE_USER, $TYPO3_CONF_VARS;

        if($status == 'delete' && $table == 'tx_ingallery_album') {
        	$this->deleteAllPictures($id);
        }
    }

    function deleteAllPictures($tx_ingallery_album_uid){
        $rec = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_ingallery_image', 'tx_ingallery_album_uid='.$tx_ingallery_album_uid, $groupBy='', $orderBy='', $limit='');
        if (isset($rec) && count($rec) > 0){
            foreach($rec as $pic){
                $this->cleanSysRefIndex($pic['uid'],$pic['image']);
            }
        }
        $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_ingallery_image', ' tx_ingallery_album_uid = '.$tx_ingallery_album_uid);
    }
}
?>