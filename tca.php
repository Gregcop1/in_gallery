<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_ingallery_album'] = array (
	'ctrl' => $TCA['tx_ingallery_album']['ctrl'],
	'interface' => array (
	'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,title,description,path_folder,tx_ingallery_category_uid'
	),
	'feInterface' => $TCA['tx_ingallery_album']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_ingallery_album',
				'foreign_table_where' => 'AND tx_ingallery_album.pid=###CURRENT_PID### AND tx_ingallery_album.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_album.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required',
			)
		),
		'description' => array (		
				'exclude' => 0,		
				'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_album.description',		
				'config' => Array (
					'type' => 'text',
					'cols' => '48',
					'rows' => '5',
					'softref' => 'typolink_tag,images,email[subst],url',
					'wizards' => Array(
						'_PADDING' => 4,
						'RTE' => Array(
							'notNewRecords' => 1,
							'RTEonly' => 1,
							'type' => 'script',
							'title' => 'LLL:EXT:cms/locallang_ttc.php:bodytext.W.RTE',
							'icon' => 'wizard_rte2.gif',
							'script' => 'wizard_rte.php',
						),
					)
				)
		),
		'path_folder' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_album.path_folder',		
			'config' => array (
				'type'     => 'input',
				'size'     => '15',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'script'       => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'tx_ingallery_category_uid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_album.tx_ingallery_category_uid',		
			'config' => array (
				'type' => 'select',	
				'items' => array (
					array('',0),
				),
				'foreign_table' => 'tx_ingallery_category',	
				'foreign_table_where' => 'ORDER BY tx_ingallery_category.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,	
				"MM" => "tx_ingallery_album_cat_mm",
			)
		),

		'tx_ingallery_image_uid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_album.tx_ingallery_image_uid',		
			'config' => array (
				'type' => 'select',	
				'items' => array (
					array('',0),
				),
				'foreign_table' => 'tx_ingallery_image',	
				'foreign_table_where' => ' AND tx_ingallery_image.pid=###CURRENT_PID### AND tx_ingallery_image.tx_ingallery_album_uid ="###THIS_UID###" AND tx_ingallery_image.sys_language_uid IN (-1,0) ORDER BY tx_ingallery_image.title',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title,description;;;;2-2-2, path_folder;;;;3-3-3, tx_ingallery_category_uid, tx_ingallery_image_uid')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime')
	)
);

$TCA['tx_ingallery_image'] = array (
	'ctrl' => $TCA['tx_ingallery_image']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,tx_ingallery_album_uid,title,legend,embed,link,date,copyright,image'
	),
	'feInterface' => $TCA['tx_ingallery_image']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_ingallery_image',
				'foreign_table_where' => 'AND tx_ingallery_image.pid=###CURRENT_PID### AND tx_ingallery_image.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'tx_ingallery_album_uid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image.tx_ingallery_album_uid',		
			'config' => array (
				'eval' => 'required',
				'type' => 'select',	
				'items' => array (
					array('', 0),
				),
				'foreign_table' => 'tx_ingallery_album',	
				'foreign_table_where' => ' AND tx_ingallery_album.pid=###CURRENT_PID### AND tx_ingallery_album.sys_language_uid IN (-1,0) ORDER BY tx_ingallery_album.title',	
				'size' => 1,	
				'minitems' => 1,
				'maxitems' => 1,
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
				'eval' => 'required',
			)
		),
		'legend' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image.legend',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'embed' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image.embed',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'link' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image.link',		
			'config' => array (
				'type'     => 'input',
				'size'     => '15',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
				'_PADDING' => 2,
				'link'     => array(
					'type'         => 'popup',
					'title'        => 'Link',
					'icon'         => 'link_popup.gif',
					'script'       => 'browse_links.php?mode=wizard',
					'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'date' => array (		
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image.date',		
			'config' => Array (
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'datetime',
				'checkbox' => '0',
				'default' => '0',
				'range' => Array (
					'upper' => mktime(0,0,0,12,31,2020),
					'lower' => mktime(0,0,0,date('m')-1,date('d'),date('Y'))
				)
			)
		),
		'source' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image.source',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'copyright' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image.copyright',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'image' => Array (
			'exclude' => 1,
			'l10n_mode' => $l10n_mode_image,
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_image.image',		
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => '10000',
				'uploadfolder' => '',
				'show_thumbs' => '1',
				'size' => 3,
				'autoSizeMax' => 15,
				'maxitems' => '99',
				'minitems' => '0'
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, tx_ingallery_album_uid, title;;;;2-2-2, image, legend, embed, link;;;;3-3-3, date, source, copyright')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);



$TCA['tx_ingallery_category'] = array (
	'ctrl' => $TCA['tx_ingallery_category']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,title'
	),
	'feInterface' => $TCA['tx_ingallery_category']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_ingallery_category',
				'foreign_table_where' => 'AND tx_ingallery_category.pid=###CURRENT_PID### AND tx_ingallery_category.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:in_gallery/locallang_db.xml:tx_ingallery_category.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title;;;;2-2-2')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime')
	)
);
?>