inouit.gallery.navEffect.slide = jQuery.extend(true,{},inouit.gallery.navEffect.default);
jQuery.extend(true,inouit.gallery.navEffect.slide, {
	name: 'slide',
	itemW: 0,
	nbItem: 0,
	currentItem: -1,
	itemList: '',

	initialize: function(){
		this.buildMiniList();
		this.buildMiniArrows();
	},

	buildMiniList: function(){
		this.container.addClass('hoverFlowHidden');
		var widthTotal = 0;
		var objMiniPicture = jQuery('.albumMiniList > li');
		objMiniPicture.each(function(){
			widthTotal = widthTotal + jQuery(this).width()+parseInt( (jQuery(this).css('marginLeft')).replace('px','') )+parseInt( (jQuery(this).css('marginRight')).replace('px','') );
		});
		jQuery('.albumMiniList').width(widthTotal+'px');

		this.bindMiniPicture();
	},

	bindMiniPicture: function () {
		var _this = this.imageEffect;
		jQuery('.albumMiniList li').click( function(){ _this.showItem(jQuery(this)); });
	},

	unBindMiniPicture: function () {
		jQuery('.albumMiniList li').unbind();
	},

	buildMiniArrows: function(){
		var miniList = this.container.find('.albumMiniList');
		if(miniList.find('li').length){
			var arrows = jQuery('.arrowsMiniContainer');

			var _this = this;
			var left = jQuery('<a/>').attr('href','javascript:;')
									.addClass('arrow')
									.addClass('arrowLeft')
									.click( function() {_this.slideLeft(); })
									.appendTo(arrows);
			var right = jQuery('<a/>').attr('href','javascript:;')
									.addClass('arrow')
									.addClass('arrowRight')
									.click( function() {_this.slideRight(); })
									.appendTo(arrows);

			miniList.hover(function(){
				_this.miniArrowsAddShow(left,right);
			},function(){
				_this.miniArrowsRemoveShow(left,right);
			});
			arrows.hover(function(){
				_this.miniArrowsAddShow(left,right);
			},function(){
				_this.miniArrowsRemoveShow(left,right);
			});
		}
	},

	miniArrowsAddShow: function (left,right) {
		left.addClass('arrowLeftShow');
		right.addClass('arrowRightShow');
	},

	miniArrowsRemoveShow: function (left,right) {
		left.removeClass('arrowLeftShow');
		right.removeClass('arrowRightShow');
	},

	slideLeft: function (){
		if ( parseInt( String(this.container.find('.albumMiniList').css('marginLeft')).replace('px','') ) < 0){
			this.container.find('.albumMiniList').animate({'marginLeft' : '+='+this.container.width()}, 'fast');
		}
	},

	slideRight: function (){
		if ( parseInt( String(this.container.find('.albumMiniList').css('marginLeft')).replace('px','') ) > '-'+this.container.width()){
			this.container.find('.albumMiniList').animate({'marginLeft' : '-='+this.container.width()}, 'fast');
		}
	},

});