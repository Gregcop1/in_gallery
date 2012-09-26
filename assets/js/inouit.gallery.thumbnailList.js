if(!inouit) {
	var inouit = {};
}

if(!inouit.gallery) {
	inouit.gallery = {};
}



inouit.gallery.thumbnailList = {
	gallery: '',
	listContainer: '',
	maskContainer: '',
	list: '',
	arrows: '',
	arrowLeft: '',
	arrowRight: '',
	currentPosition: 0,

	initialize: function() {
		this.buildList();
		this.refreshWidth();
	},

	buildList: function() {
		listContainer = jQuery('<div/>').addClass('galleryThumbnailList');
		this.buildArrow();
		
		maskContainer = jQuery('<div/>').addClass('mask')
									.appendTo(listContainer)
		list = jQuery('<ul/>').addClass('thumbnails')
								.appendTo(maskContainer);
		var item = '';
		var _this = this;
		this.gallery.container.find('.thumbnail').each(function(n,i){
			jQuery(this).addClass('inactive');
			item = jQuery('<li/>').addClass('miniItemListNb_'+n).addClass(jQuery(this).attr('id'))
								.click( function() { _this.gallery.showItem(jQuery(this)) })
								.append(this);
			list.append(item);
		});

		this.gallery.container.after(listContainer);
	},

	buildArrow: function() {
		arrows = jQuery('<div/>').addClass('thumbnailNavigation')
								.appendTo(listContainer);
		arrowLeft = jQuery('<a/>').attr('href','javascript:;')
									.addClass('arrow')
									.addClass('arrowLeft')
									.click(this.slideLeft)
									.appendTo(arrows);
		arrowRight = jQuery('<a/>').attr('href','javascript:;')
								.addClass('arrow')
								.addClass('arrowRight')
								.click(this.slideRight)
								.appendTo(arrows);
	},

	refreshWidth: function() {
		var widthTotal = 0;
		var objMiniPicture = list.children('li');
		objMiniPicture.each(function(){
			widthTotal = widthTotal + jQuery(this).width()+parseFloat( (jQuery(this).css('marginLeft')).replace('px','') )+parseFloat( (jQuery(this).css('marginRight')).replace('px','') )+parseFloat( (jQuery(this).css('paddingLeft')).replace('px','') )+parseFloat( (jQuery(this).css('paddingRight')).replace('px','')+parseFloat( jQuery(this).css('borderLeftWidth').replace('px',''))+parseFloat( jQuery(this).css('borderRightWidth').replace('px','')) );
		});
		
		list.width(widthTotal+'px');
	},

	slideLeft: function (){
		var newPos = 0;
		var notYet = true;
		var maxWidth = inouit.gallery.thumbnailList.currentPosition - maskContainer.width();
		if( inouit.gallery.thumbnailList.currentPosition > 0 ){
			list.children('li').each(function(){
				if(notYet) {
					if( ((newPos + parseFloat(jQuery(this).css('marginLeft')) + parseFloat(jQuery(this).width())) <= maxWidth) ){
						newPos += parseFloat(jQuery(this).css('marginLeft')) + parseFloat(jQuery(this).width()) + parseFloat(jQuery(this).css('marginRight'))
					}else {
						if(newPos != 0 ){
							newPos += parseFloat(jQuery(this).css('marginLeft')) + parseFloat(jQuery(this).width()) + parseFloat(jQuery(this).css('marginRight'))
						}
						notYet = false;
					}
				}
			});
			inouit.gallery.thumbnailList.currentPosition = newPos;
			list.animate({'marginLeft' : '-'+newPos}, 'fast');
		}
	},

	slideRight: function (){
		var newPos = 0;
		var notYet = true;
		var maxWidth = inouit.gallery.thumbnailList.currentPosition + maskContainer.width();
		if( maxWidth < list.width() ){
			list.children('li').each(function(){
				if( notYet && ((newPos + parseFloat(jQuery(this).css('marginLeft')) + parseFloat(jQuery(this).width())) <= maxWidth) ){
					newPos += parseFloat(jQuery(this).css('marginLeft')) + parseFloat(jQuery(this).width()) + parseFloat(jQuery(this).css('marginRight'))
				}else {
					notYet = false;
				}
			});
			inouit.gallery.thumbnailList.currentPosition = newPos;
			list.animate({'marginLeft' : '-'+newPos}, 'fast');
		}
	},

	refreshActiveThumbnail: function(currentId){
		list.find('.active').removeClass('active');
		list.children('li.itemMiniPic_'+currentId).children('img').addClass('active');
	}
}