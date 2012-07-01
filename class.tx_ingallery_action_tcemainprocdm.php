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

class tx_ingallery_action_tcemainprocdm
{
    var $extConf;

    function processDatamap_postProcessFieldArray ($status, $table, $id, &$fieldArray, &$reference){
        global $FILEMOUNTS, $BE_USER, $TYPO3_CONF_VARS;


        if($table == 'tx_ingallery_album'){
            $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['in_gallery']);

            if($status == 'new'){
                //t3lib_div::debug(array(0 => ($this->getAlbumUid()+1), 1 => $fieldArray,2 => $reference));die();
                if (isset($fieldArray['path_folder']) && !empty($fieldArray['path_folder'])){
                    $pathFolder    = $_SERVER['DOCUMENT_ROOT'].$fieldArray['path_folder'];
                    //t3lib_div::debug(array(0 => $pathFolder));
                    $refPathFolder = opendir($pathFolder);
                    $uid = ($this->getAlbumUid()+1);
                    $sorting = ($this->getImageSorting($uid)+1);
                    while (false !== ($file = readdir($refPathFolder))) {
                        if(filetype($pathFolder . $file) == 'file'){
                            $infosPicture = getimagesize($pathFolder . $file);
                            $mimeType = $infosPicture['mime'];
                            if($mimeType == 'image/jpeg' || $mimeType == 'image/png' || $mimeType == 'image/gif'){
                                if ($infosPicture[0] > $this->extConf['maxWidth'] || $infosPicture[1] > $this->extConf['maxHeight']){
                                    $this->createResizePicture($pathFolder,$file,$infosPicture,$mimeType);
                                }
                                $this->createThumb($pathFolder,$pathFolder.'Thumbs/',$file,0.2,$infosPicture[0],$infosPicture[1],$mimeType);
                                $this->insertNewPicture($file,$fieldArray['pid'],$sorting,$uid);
                                $sorting++;
                            }
                        }
                    }
                }
            }
            if($status == 'update'){

            }
        }

        if($table == 'tx_ingallery_image'){
            $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['in_gallery']);

            if($status == 'new'){
                $fieldArray['sorting'] = ($this->getImageSorting($fieldArray['tx_ingallery_album_uid'])+1);
                    
                t3lib_div::debug(array(1 => $fieldArray,2 => $reference));die();
            }
        }
    }

    function createResizePicture($pathFolderSrc,$file,$infosPicture,$ext){
        
        $width  = $infosPicture[0];
        $height = $infosPicture[1];

        if ($width >= $height){
            $newwidth   = $this->extConf['maxWidth'];
            $newheight  = ($height/$width) * $this->extConf['maxWidth'];
            //t3lib_div::debug(array($width => $newwidth,$height => $newheight));die();
        }else{
            $newwidth   = ($width/$height) * $this->extConf['maxHeight'];
            $newheight  = $this->extConf['maxHeight'];
            //t3lib_div::debug(array($width => $newwidth,$height => $newheight));die();
        }
        $this->createPicture($pathFolderSrc,$pathFolderSrc,$file,$newwidth,$newheight,$width,$height,$ext);
    }

    function createThumb($pathFolderSrc,$pathFolderDst,$file,$percent = 0.5,$width,$height,$ext){
        if (! file_exists( $pathFolderDst )){
            mkdir($pathFolderDst);
        }

        $newwidth = $width * $percent;
        $newheight = $height * $percent;

        $this->createPicture($pathFolderSrc,$pathFolderDst,$file,$newwidth,$newheight,$width,$height,$ext);

    }

    function createPicture($pathFolderSrc,$pathFolderDst,$file,$newwidth,$newheight,$width,$height,$ext) {
        $newImg = imagecreatetruecolor($newwidth, $newheight);
        if ($ext == 'image/jpeg')
            $srcImg = imagecreatefromjpeg($pathFolderSrc.$file);
        if ($ext == 'image/png')
            $srcImg = imagecreatefrompng($pathFolderSrc.$file);
        if ($ext == 'image/gif')
            $srcImg = imagecreatefromgif($pathFolderSrc.$file);

        imagecopyresized($newImg, $srcImg, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        
        if ($ext == 'image/jpeg')
            imagejpeg($newImg,$pathFolderDst.$file,100);
        if ($ext == 'image/png')
            imagepng($newImg,$pathFolderDst.$file,100);
        if ($ext == 'image/gif')
            imagegif($newImg,$pathFolderDst.$file,100);

        imagedestroy($newImg);
        imagedestroy($srcImg);    
    }

    function getAlbumUid () {
       $total = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*) as total', 'tx_ingallery_album', '', $groupBy='', $orderBy='', $limit='');
       return $total[0]['total']; 
    }

    function getImageSorting ($tx_ingallery_album_uid) {
       $total = $GLOBALS['TYPO3_DB']->exec_SELECTquery('sorting', 'tx_ingallery_image', ' tx_ingallery_album_uid = '.$tx_ingallery_album_uid, $groupBy='', $orderBy=' sorting DESC', $limit='1');
       return $total[0]['sorting']; 
    }

    function insertNewPicture($file,$pid,$sorting,$tx_ingallery_album_uid){
        $insertFields = array(
            'title'                             => $file,
            'pid'                               => $pid,
            'legend'                            => '',
            'date'                              => time(),
            'image'                             => $file,
            'source'                            => '',
            'copyright'                         => '',
            'hidden'                            => 0,
            'deleted'                           => 0,
            'l10n_parent'                       => intval($GLOBALS['TSFE']->sys_language_uid),
            'sys_language_uid'                  => intval($GLOBALS['TSFE']->sys_language_uid),
            'sorting'                           => $sorting,
            'tx_ingallery_album_uid'            => $tx_ingallery_album_uid
        );
        $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_ingallery_image',$insertFields);
    }
}
?>
