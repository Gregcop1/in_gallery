<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$TCA['tx_ingallery_album'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_album',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'sortby' => 'sorting',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'starttime' => 'starttime',	
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_ingallery_album.gif',
	),
);

$TCA['tx_ingallery_image'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'sortby' => 'sorting',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_ingallery_image.gif',
	),
);



t3lib_extMgm::addPlugin(array(
	'LLL:EXT:in_gallery/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


// Adding flexform
$TCA["tt_content"]["types"]["list"]["subtypes_addlist"][$_EXTKEY."_pi1"]="pi_flexform"; 
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/pi1/flexform.xml');

?>
