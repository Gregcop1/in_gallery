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

class tx_ingallery_action_tcemain
{
    var $extConf;
    var $hashFile       = 1;
    
    function processDatamap_postProcessFieldArray ($status, $table, $id, &$fieldArray, &$reference){
        global $FILEMOUNTS, $BE_USER, $TYPO3_CONF_VARS;

        $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['in_gallery']);

        if($table == 'tx_ingallery_album'){
            $this->manageAlbumEvent($status, $table, $id, $fieldArray, $reference);            
        }elseif($table == 'tx_ingallery_image'){
            $this->manageImageEvent($status, $table, $id, $fieldArray, $reference);
        }
    }

    function processCmdmap_preProcess($status, $table, $id, &$fieldArray, &$reference) {
        $this->processDatamap_postProcessFieldArray($status, $table, $id, $fieldArray, $reference);
    }

    function manageAlbumEvent($status, $table, $id, &$fieldArray, &$reference) {
        
        switch($status) {
            case 'new':
                if (isset($fieldArray['path_folder']) && !empty($fieldArray['path_folder'])){
                    $this->createAndInsertNewPicture($id,$fieldArray);
                }
            break;
            case 'update':
                $albumFolderPath = $this->getAlbumFolderPath($id);
                if (isset($fieldArray['path_folder']) && !empty($fieldArray['path_folder'])){
                    if ($fieldArray['path_folder'] == $albumFolderPath){
                        $this->createAndInsertNewPicture($id,$fieldArray);
                    }else{
                        $this->deleteAllPictures($id);
                        $this->createAndInsertNewPicture($id,$fieldArray);
                        $fieldArray['tx_ingallery_image_uid'] = '0';
                    }
                }else{
                    $fieldArray['path_folder'] = $albumFolderPath;
                    $this->createAndInsertNewPicture($id,$fieldArray);
                }
            break;
            case 'delete': 
                $this->deleteAllPictures($id);
            break;
        }
    }

    function manageImageEvent($status, $table, $id, &$fieldArray, &$reference) {
        switch($status) {
            case 'new':
                $fieldArray['sorting'] = ($this->getImageSorting($fieldArray['pid'],$fieldArray['tx_ingallery_album_uid'])+1);
                $albumFolderPath = $this->getAlbumFolderPath($fieldArray['tx_ingallery_album_uid']);
                $fullPathFolder    = $_SERVER['DOCUMENT_ROOT'].$albumFolderPath;
                $this->checkNewPicture($id,$fieldArray,$albumFolderPath,$fullPathFolder,str_replace($albumFolderPath,'',$fieldArray['image']),$sorting,$uid,false);
            break;
            case 'update':
                if (isset($fieldArray['image']) && !empty($fieldArray['image'])){
                    $album_uid = $this->getImageAlbumUid($id);
                    $albumFolderPath = $this->getAlbumFolderPath($album_uid);
                    $fullPathFolder    = $_SERVER['DOCUMENT_ROOT'].$albumFolderPath;

                    $this->cleanSysRefIndex($id,$fieldArray['image']);

                    $this->checkNewPicture($id,$fieldArray,$albumFolderPath,$fullPathFolder,str_replace($albumFolderPath,'',$fieldArray['image']),$sorting,$uid,false);
                }
            break;
        }
    }

    function createAndInsertNewPicture($id,$fieldArray){
        if($fieldArray['path_folder']) {
            $fullPathFolder    = $_SERVER['DOCUMENT_ROOT'].'/'.$fieldArray['path_folder'];

            //test if dir exist and is not empty
            if($fullePefPathFolder = opendir($fullPathFolder) ) {
                $files = scandir($fullPathFolder);
                if(count($files) > 2) {
                    $uid = intval($id);
                    if (!$uid){
                        $uid = ($this->getAlbumUid()+1);
                    }
                    $sorting = ($this->getImageSorting($uid,$fieldArray['tx_ingallery_album_uid'])+1);
                    while (false !== ($file = readdir($fullePefPathFolder))) {
                        if ($this->checkNewPicture($id,$fieldArray,$fieldArray['path_folder'],$fullPathFolder,$file,$sorting,$uid,true)){
                            $sorting++;
                        }
                    }
                }
            }
        }
    }

    function checkNewPicture($id,$fieldArray,$path_folder,$fullPathFolder,$file,$sorting,$uid=0,$insert=true){
        if(filetype($fullPathFolder . $file) == 'file'){
            $infosPicture = getimagesize($fullPathFolder . $file);
            $mimeType = $infosPicture['mime'];
            if($mimeType == 'image/jpeg' || $mimeType == 'image/png' || $mimeType == 'image/gif'){
                $fileExists = $this->getImageByImage($id,$path_folder.$file);
                if (empty($fileExists)){
                    if ($infosPicture[0] > $this->extConf['maxWidth'] || $infosPicture[1] > $this->extConf['maxHeight']){
                        $this->createResizePicture($fullPathFolder,$file,$infosPicture,$mimeType);
                    }
                    $this->createThumb($fullPathFolder,$fullPathFolder.'Thumbs/',$file,0.2,$infosPicture[0],$infosPicture[1],$mimeType);
                    $pid = $fieldArray['pid'];
                    if (!$pid){
                        $pid = $this->getAlbumPid($id);
                    }
                    if ($insert){
                        $this->insertNewPicture($path_folder,$fullPathFolder,$file,$pid,$sorting,$uid);
                    }
                    return true;
                }
            }
        }
        return false;
    }

    function createResizePicture($fullPathFolderSrc,$file,$infosPicture,$ext){

        $width  = $infosPicture[0];
        $height = $infosPicture[1];

        if ($width >= $height){
            $newwidth   = $this->extConf['maxWidth'];
            $newheight  = ($height/$width) * $this->extConf['maxWidth'];
        }else{
            $newwidth   = ($width/$height) * $this->extConf['maxHeight'];
            $newheight  = $this->extConf['maxHeight'];
        }
        $this->createPicture($fullPathFolderSrc,$fullPathFolderSrc,$file,$newwidth,$newheight,$width,$height,$ext);
    }

    function createThumb($fullPathFolderSrc,$fullPathFolderDst,$file,$percent = 0.5,$width,$height,$ext){
        if (! file_exists( $fullPathFolderDst )){
            mkdir($fullPathFolderDst);
        }

        $newwidth = $width * $percent;
        $newheight = $height * $percent;

        $this->createPicture($fullPathFolderSrc,$fullPathFolderDst,$file,$newwidth,$newheight,$width,$height,$ext);

    }

    function createPicture($fullPathFolderSrc,$fullPathFolderDst,$file,$newwidth,$newheight,$width,$height,$ext) {
        $newImg = imagecreatetruecolor($newwidth, $newheight);
        if ($ext == 'image/jpeg')
            $srcImg = imagecreatefromjpeg($fullPathFolderSrc.$file);
        if ($ext == 'image/png')
            $srcImg = imagecreatefrompng($fullPathFolderSrc.$file);
        if ($ext == 'image/gif')
            $srcImg = imagecreatefromgif($fullPathFolderSrc.$file);

        imagecopyresized($newImg, $srcImg, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        if ($ext == 'image/jpeg')
            imagejpeg($newImg,$fullPathFolderDst.$file,100);
        if ($ext == 'image/png')
            imagepng($newImg,$fullPathFolderDst.$file,100);
        if ($ext == 'image/gif')
            imagegif($newImg,$fullPathFolderDst.$file,100);

        imagedestroy($newImg);
        imagedestroy($srcImg);
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

    function getAlbumUid () {
        $rec = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('count(*) as total', 'tx_ingallery_album', '', $groupBy='', $orderBy='', $limit='');
        return $rec[0]['total'];
    }

    function getAlbumPid ($uid) {
        $rec = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('pid', 'tx_ingallery_album', 'tx_ingallery_album.uid = '.$uid, $groupBy='', $orderBy='', $limit='');
        return $rec[0]['pid'];
    }

    function getAlbumFolderPath ($uid) {
        $rec = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('path_folder', 'tx_ingallery_album', 'tx_ingallery_album.uid = '.$uid, $groupBy='', $orderBy='', $limit='');
        return $rec[0]['path_folder'];
    }

    function getImageSorting ($pid,$tx_ingallery_album_uid) {
        $rec = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('sorting', 'tx_ingallery_image', ' tx_ingallery_image.pid = \''.intval($pid).'\' AND tx_ingallery_album_uid = \''.$tx_ingallery_album_uid.'\'', $groupBy='', $orderBy=' sorting DESC', $limit='1');
        return $rec[0]['sorting'];
    }

    function getImageByImage ($tx_ingallery_album_uid = '',$file) {
        $rec = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('image', 'tx_ingallery_image', ' tx_ingallery_image.tx_ingallery_album_uid = \''.intval($tx_ingallery_album_uid).'\' AND tx_ingallery_image.image = \''.$file.'\'', $groupBy='', $orderBy=' sorting DESC', $limit='1');
        return $rec[0]['image'];
    }

    function getImageAlbumUid ($uid) {
        $rec = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('tx_ingallery_album_uid', 'tx_ingallery_image', 'tx_ingallery_image.uid = '.$uid, $groupBy='', $orderBy='', $limit='');
        return $rec[0]['tx_ingallery_album_uid'];
    }

    function insertNewPicture($path_folder,$fullPathFolder,$file,$pid,$sorting,$tx_ingallery_album_uid){

        $insertFields = array(
            'title'                             => $file,
            'pid'                               => $pid,
            'legend'                            => '',
            'date'                              => time(),
            'image'                             => $path_folder.$file,
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
        unset($insertFields);

        $rec = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid', 'tx_ingallery_image', ' tx_ingallery_image.pid = \''.intval($pid).'\' AND title = \''.$file.'\'  AND image = \''.$file.'\'', $groupBy='', $orderBy=' sorting DESC', $limit='1');

        $insertFields = array(
            'tablename'     =>  'tx_ingallery_image',
            'recuid'        =>  $rec[0]['uid'],
            'field'         =>  'image',
            'flexpointer'   =>  '',
            'softref_key'   =>  '',
            'softref_id'    =>  '',
            'sorting'       =>  '0',
            'deleted'       =>  '0',
            'ref_table'     =>  '_FILE',
            'ref_uid'       =>  '0',
            'ref_string'    =>  $path_folder.$file
        );
        $insertFields['hash']   =  md5(implode('///', $insertFields) . '///' . $this->hashFile);
        $GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_refindex',$insertFields);
        unset($insertFields);
    }

    function cleanSysRefIndex ($recuid,$ref_string){

        $deleteFields = array(
            'tablename'     =>  '\'tx_ingallery_image\'',
            'recuid'        =>  '\''.$recuid.'\'',
            'field'         =>  '\'image\'',
            'ref_table'     =>  '\'_FILE\'',
            'ref_string'    =>  '\''.$ref_string.'\''
        );

        $GLOBALS['TYPO3_DB']->exec_DELETEquery('sys_refindex',implode(' AND ',$deleteFields));
    }
}
?>
