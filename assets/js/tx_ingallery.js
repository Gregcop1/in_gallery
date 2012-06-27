/***************************************************************
*  Copyright notice
*
*  (c) 2011 Sullivan Atatri <satatri@inouit.com>
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
jQuery(document).ready(function() {

    miniatureNavWidth = 904;    // Width of 'ul' miniature
    miniaturePerPage  = 9;      // Number of miniature per page
    slideTimeout      = 8000;   // Time in ms to next slide
    miniatureOpacity  = '1'     // Opacity miniature
    miniatureOpacityH = '0.75'; // Opacity on hover miniature
    

    /**************************************
    *************** Properties ************
    ***************************************/
    var currentAlbum; // Current album
    var maxImg; // Total images in gallery
    var currentImg; // Current image on gallery
    var timeout; // Stock timeout function for auto-slide
    var imageWidth; // Get image width of gallery
    var minnavWidth = 0; // Use to auto-increment miniature image nav
    var currentBullet = 0; // Current active bullet navigation
    var maxBullet = 0; // Bullets count
    


    
    /***************************************
    ********* Regroup  functions ***********
    ****************************************/
    function slide(dir){
        if(dir=='next'){
            if(currentImg == maxImg){
                currentImg = 0;
                jQuery(currentAlbum).find('ul.tx_ingallery_ul').animate({'marginLeft' : '0'});
            }
            else{
                currentImg += 1;
                jQuery(currentAlbum).find('ul.tx_ingallery_ul').animate({'marginLeft' : (parseInt(jQuery(currentAlbum).find('ul.tx_ingallery_ul').css('marginLeft')) - imageWidth)});
            }
        }
        else if(dir=='prev'){
            if(currentImg == 0){
                currentImg = maxImg;
                jQuery(currentAlbum).find('ul.tx_ingallery_ul').animate({'marginLeft' : '-'+(imageWidth*maxImg)});
            }
            else{
                currentImg -=1;
                jQuery(currentAlbum).find('ul.tx_ingallery_ul').animate({'marginLeft' : '+='+imageWidth});
            }
        }
        getImagePagination();
        clearTimeout(timeout);
        timeout = setTimeout(function(){slide('next')},slideTimeout);
    }
    
    function navClick(dir){
        if(dir=='left'){
            if(parseInt(jQuery('.tx_ingallery_minnav').css('marginLeft')) !== 0){
                jQuery('.tx_ingallery_minnav').animate({'marginLeft' : '+='+miniatureNavWidth});
                updateBullet(dir);
            }
        }
        else if(dir=='right'){
            if(currentBullet !== (maxBullet - 1)){
                jQuery('.tx_ingallery_minnav').animate({'marginLeft' : '-='+miniatureNavWidth});
                updateBullet(dir);
            }
        }
    }
    
    function updateBullet(obj){
        if(maxImg > miniaturePerPage){
            if(jQuery(obj).attr('class') == 'tx_ingallery_bulletleft'){
                if(currentBullet !== 0){
                    jQuery('#tx_ingallery_bullet'+currentBullet).removeClass('tx_ingallery_bullet_on').addClass('tx_ingallery_bullet');
                    currentBullet -=1;
                    jQuery('#tx_ingallery_bullet'+currentBullet).addClass('tx_ingallery_bullet_on');
                    navClick('left');
                }
            }
            else if(jQuery(obj).attr('class') == 'tx_ingallery_bulletright'){
                if(currentBullet < Math.round(maxImg / miniaturePerPage)){
                    jQuery('#tx_ingallery_bullet'+currentBullet).removeClass('tx_ingallery_bullet_on').addClass('tx_ingallery_bullet');
                    currentBullet += 1;
                    jQuery('#tx_ingallery_bullet'+currentBullet).addClass('tx_ingallery_bullet_on');
                    navClick('right');
                }
            }
            else if(obj == 'left'){
                if(currentBullet !== 0){
                    jQuery('#tx_ingallery_bullet'+currentBullet).removeClass('tx_ingallery_bullet_on').addClass('tx_ingallery_bullet');
                    currentBullet -=1;
                    jQuery('#tx_ingallery_bullet'+currentBullet).addClass('tx_ingallery_bullet_on');
                }
            }
            else if(obj == 'right'){
                if(currentBullet < Math.round(maxImg / miniaturePerPage)){
                    jQuery('#tx_ingallery_bullet'+currentBullet).removeClass('tx_ingallery_bullet_on').addClass('tx_ingallery_bullet');
                    currentBullet += 1;
                    jQuery('#tx_ingallery_bullet'+currentBullet).addClass('tx_ingallery_bullet_on');
                }
            }
        }
    }
    
    function getImagePagination(){
        jQuery('.tx_ingallery_image_page').text((currentImg+1)+'/'+(maxImg+1));
    }
    
    
    
    /***************************************
    ******* Regroup procedural code ********
    ****************************************/

    /* First step display first album only
     * Stock imageWidth / currentAlbum / currentImg */
    jQuery('.tx_ingallery_album').each(function(index){
        if(index == 0){
            imageWidth   = parseInt(jQuery(this).find('li').css('width'));
            currentAlbum = this;
            currentImg   = index;
            jQuery(currentAlbum).children('.tx_ingallery_image').find('li').each(function(index){
                maxImg = index;
            });
            jQuery(this).find('ul.tx_ingallery_ul').css('width', ((maxImg+1)*imageWidth));
        }
        else{
            jQuery(this).css('display', 'none');
        }
    });
    
    
    // Create miniature images for navigation 
    jQuery('<span class="tx_ingallery_minleft"></span>').appendTo(jQuery(currentAlbum).find('.tx_ingallery_image_nav'));
    jQuery('<ul class="tx_ingallery_minnav"></ul>').appendTo(jQuery(currentAlbum).find('.tx_ingallery_image_nav'));
    jQuery(currentAlbum).find('li > img').each(function(index){
        minnavWidth += 113;
        jQuery('<li><img src="'+jQuery(this).attr('src')+'"  height="55" alt=""></li>').appendTo(jQuery('.tx_ingallery_minnav'));
    });
    jQuery('.tx_ingallery_minnav').css('width',minnavWidth);
    jQuery('<span class="tx_ingallery_minright"></span>').appendTo(jQuery(currentAlbum).find('.tx_ingallery_image_nav'));
    jQuery('.tx_ingallery_minnav > li > img').each(function(index){
        jQuery(this).hover(
            function(){
                jQuery(this).css('opacity',miniatureOpacityH);
            },
            function(){
                jQuery(this).css('opacity',miniatureOpacity);
            }
        );
        jQuery(this).click(function(){
            clearTimeout(timeout);
            jQuery(currentAlbum).find('ul.tx_ingallery_ul').animate({'marginLeft' : '-'+imageWidth*index});
            currentImg = index;
            getImagePagination();
            timeout = setTimeout(function(){slide('next')},slideTimeout);
        });
    });
    
    
    
    // Create bullet navigation for miniature images 
    jQuery('<div class="tx_ingallery_bulletnav"><span class="tx_ingallery_bulletleft"></span></div>').appendTo(jQuery('.tx_ingallery_bottom_nav'));

    for(var i=0;i<Math.floor(maxImg/miniaturePerPage);i++){
        if(i==0){
            jQuery('<span bullet="'+i+'" class="tx_ingallery_bullet_on" id="tx_ingallery_bullet'+i+'"></span>').appendTo(jQuery('.tx_ingallery_bulletnav'));
        }
        else{
            jQuery('<span bullet="'+i+'" class="tx_ingallery_bullet" id="tx_ingallery_bullet'+i+'"></span>').appendTo(jQuery('.tx_ingallery_bulletnav'));
        }
        jQuery('#tx_ingallery_bullet'+i).click(function(){
            if(parseInt(jQuery(this).attr('bullet')) == 0){
                jQuery('#tx_ingallery_bullet'+currentBullet).removeClass('tx_ingallery_bullet_on').addClass('tx_ingallery_bullet');
                jQuery('.tx_ingallery_minnav').animate({'marginLeft' : '0'});
                currentBullet = 0;
                jQuery('#tx_ingallery_bullet'+currentBullet).addClass('tx_ingallery_bullet_on');
            }
            else{
                jQuery('#tx_ingallery_bullet'+currentBullet).removeClass('tx_ingallery_bullet_on').addClass('tx_ingallery_bullet');
                jQuery('.tx_ingallery_minnav').animate({'marginLeft' : '-'+miniatureNavWidth*parseInt(jQuery(this).attr('bullet'))});
                currentBullet = parseInt(jQuery(this).attr('bullet'));
                jQuery('#tx_ingallery_bullet'+currentBullet).addClass('tx_ingallery_bullet_on');
            }
        });
        ++maxBullet;
    }
    jQuery('<span class="tx_ingallery_bulletright"></span>').appendTo(jQuery('.tx_ingallery_bulletnav'));;
    

    // Container to pagination text (ex: 2/16) 
    jQuery('<div class="tx_ingallery_image_page"></div>').appendTo(jQuery('.tx_ingallery_bottom_nav'));

    /**************************************
    ******* Regroup click function ********
    ***************************************/
    jQuery('.tx_ingallery_leftbtn').click(function(){slide('prev');});
    jQuery('.tx_ingallery_rightbtn').click(function(){slide('next');});
    jQuery('.tx_ingallery_minleft').click(function(){navClick('left');});
    jQuery('.tx_ingallery_minright').click(function(){navClick('right');});
    jQuery('.tx_ingallery_bulletleft').click(function(){updateBullet(this);});
    jQuery('.tx_ingallery_bulletright').click(function(){updateBullet(this);});
    
    
    // Set timeout to auto-slide
    getImagePagination();
    timeout = setTimeout(function(){slide('next')},slideTimeout);
});
