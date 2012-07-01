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
    var $hashFile       = 1;
    
    function processDatamap_postProcessFieldArray ($status, $table, $id, &$fieldArray, &$reference){
        global $FILEMOUNTS, $BE_USER, $TYPO3_CONF_VARS;


        if($table == 'tx_ingallery_album'){
            $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['in_gallery']);

            if($status == 'new'){
                if (isset($fieldArray['path_folder']) && !empty($fieldArray['path_folder'])){
                    $this->createAndInsertNewPicture($id,$fieldArray);
                }
            }
            if($status == 'update'){
                $path_folder = $this->getAlbumFolder($id);
                if (isset($fieldArray['path_folder']) && !empty($fieldArray['path_folder'])){
                    if ($fieldArray['path_folder'] == $path_folder){
                        $this->createAndInsertNewPicture($id,$fieldArray);
                    }else{
                        $this->deleteAllPictures($id);  
                        $this->createAndInsertNewPicture($id,$fieldArray);  
                        $fieldArray['tx_ingallery_image_uid'] = '0';
                    }
                }else{
                    $fieldArray['path_folder'] = $path_folder;
                    $this->createAndInsertNewPicture($id,$fieldArray);
                }
            }
        }

        if($table == 'tx_ingallery_image'){
            $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['in_gallery']);

            if($status == 'new'){
                $fieldArray['sorting'] = ($this->getImageSorting($fieldArray['pid'],$fieldArray['tx_ingallery_album_uid'])+1);
                $path_folder = $this->getAlbumFolder($fieldArray['tx_ingallery_album_uid']);
                $pathFolder    = $_SERVER['DOCUMENT_ROOT'].$path_folder;
                $this->checkNewPicture($id,$fieldArray,$pathFolder,str_replace($path_folder,'',$fieldArray['image']),$sorting,$uid,false);
            }
        }
    }

    function createAndInsertNewPicture($id,$fieldArray){
        $pathFolder    = $_SERVER['DOCUMENT_ROOT'].$fieldArray['path_folder'];
        $refPathFolder = opendir($pathFolder);
        $uid = intval($id);
        if (!$uid){
            $uid = ($this->getAlbumUid()+1);
        }
        $sorting = ($this->getImageSorting($uid,$fieldArray['tx_ingallery_album_uid'])+1);
        while (false !== ($file = readdir($refPathFolder))) {
            if ($this->checkNewPicture($id,$fieldArray,$pathFolder,$file,$sorting,$uid,true)){
                $sorting++;
            }
        }        
    }
    
    function checkNewPicture($id,$fieldArray,$pathFolder,$file,$sorting,$uid=0,$insert=true){
        if(filetype($pathFolder . $file) == 'file'){
            $infosPicture = getimagesize($pathFolder . $file);
            $mimeType = $infosPicture['mime'];
            if($mimeType == 'image/jpeg' || $mimeType == 'image/png' || $mimeType == 'image/gif'){
                $fileExists = $this->getImageByImage($id,$file);
                if (empty($fileExists)){
                    if ($infosPicture[0] > $this->extConf['maxWidth'] || $infosPicture[1] > $this->extConf['maxHeight']){
                        $this->createResizePicture($pathFolder,$file,$infosPicture,$mimeType);
                    }
                    $this->createThumb($pathFolder,$pathFolder.'Thumbs/',$file,0.2,$infosPicture[0],$infosPicture[1],$mimeType);
                    $pid = $fieldArray['pid'];
                    if (!$pid){
                        $pid = $this->getAlbumPid($id);
                    }
                    if ($insert){
                        $this->insertNewPicture($pathFolder,$file,$pid,$sorting,$uid);
                    }
                    return true;
                }
            }
        }
        return false;        
    }

    function createResizePicture($pathFolderSrc,$file,$infosPicture,$ext){
        
        $width  = $infosPicture[0];
        $height = $infosPicture[1];

        if ($width >= $height){
            $newwidth   = $this->extConf['maxWidth'];
            $newheight  = ($height/$width) * $this->extConf['maxWidth'];
        }else{
            $newwidth   = ($width/$height) * $this->extConf['maxHeight'];
            $newheight  = $this->extConf['maxHeight'];
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

    function deleteAllPictures($tx_ingallery_album_uid){
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

    function getAlbumFolder ($uid) {
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

    function insertNewPicture($pathFolder,$file,$pid,$sorting,$tx_ingallery_album_uid){
        $pathFolder = str_replace($_SERVER['DOCUMENT_ROOT'],'',$pathFolder);
        
        $insertFields = array(
            'title'                             => $file,
            'pid'                               => $pid,
            'legend'                            => '',
            'date'                              => time(),
            'image'                             => $pathFolder.$file,
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
            'ref_string'    =>  $pathFolder.$file
        );
        $insertFields['hash']   =  md5(implode('///', $insertFields) . '///' . $this->hashFile);
        $GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_refindex',$insertFields);
        unset($insertFields);
    }
}
?>
