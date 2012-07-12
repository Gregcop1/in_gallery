if(!inouit) {
	var inouit = {};
}

if(!inouit.gallery) {
	inouit.gallery = {};
}

inouit.gallery.image = {

	initialize: function(container, effect, options){
		if(effect) {
			var effect = jQuery.extend(true,{},effect);
		
			effect.options = jQuery.extend(true,effect.options, options);
			effect.container = container;
			effect.api = this;
			
			effect.initialize();
		}else {
			console.log('You must choose an existing effect')
		}
	},
};


if(!inouit.gallery.effect){
	inouit.gallery.effect = {}
}
inouit.gallery.effect.default = {
	name: 'default',
	api: '',
	container: '',
	options: {
		timerDuration: 3000,
		effectDuration: 500,
		loop: true,
		fancyBoxAlbum: false,
		fancyBoxImage: false,
	},

	initialize: function(){
		this.buildContainer();
		this.placeImage();
		this.buildArrows();
		this.buildMiniList();
		this.buildMiniArrows();
		this.loadFancyBox();
		this.launch();
	},

	buildContainer: function(){},
	
	buildMiniList: function(){
		var _this = this;
		jQuery('.galleryContenerMiniList').addClass('hoverFlowHidden');
		jQuery('.albumMiniList li').click( function(){ _this.showItem(jQuery(this),_this.timer); });

		var widthTotal = 0;
		var objMiniPicture = jQuery('.albumMiniList > li');
		objMiniPicture.each(function(){
			widthTotal = widthTotal + jQuery(this).width()+parseInt( (jQuery(this).css('marginLeft')).replace('px','') )+parseInt( (jQuery(this).css('marginRight')).replace('px','') );
		});
		jQuery('.albumMiniList').width(widthTotal+'px');		
	},

	placeImage: function(){
		var cHeight = this.container.height();
		var objPicture = this.container.children('.item').children('img');
		if (objPicture.length == 0){
			objPicture = this.container.children('.item').children('a').children('img');
		}
		objPicture.each(function(){
			var iHeight = jQuery(this).attr('height');
			if(cHeight > iHeight){
				jQuery(this).css({ 'margin-top': ((cHeight-iHeight)/2)+'px' })
			}
		});
	},

	buildArrows: function(){
		if(this.container.find('.item').length){
			var arrows = jQuery('<div/>').addClass('arrowsContainer')
										.appendTo(this.container);

			var _this = this;
			var left = jQuery('<a/>').attr('href','javascript:;')
									.addClass('arrow')
									.addClass('arrowLeft')
									.click( function() {_this.prevItem(); })
									.appendTo(arrows);
			var right = jQuery('<a/>').attr('href','javascript:;')
									.addClass('arrow')
									.addClass('arrowRight')
									.click( function() {_this.nextItem(); })
									.appendTo(arrows);
		}
	},

	buildMiniArrows: function(){
		var miniList = jQuery('.albumMiniList');
		if(miniList.find('li').length){
			var arrows = jQuery('.arrowsMiniContainer');

			var _this = this;
			var left = jQuery('<a/>').attr('href','javascript:;')
									.addClass('arrow')
									.addClass('arrowLeft')
									.click( function() {_this.prevMiniItem(); })
									.appendTo(arrows);
			var right = jQuery('<a/>').attr('href','javascript:;')
									.addClass('arrow')
									.addClass('arrowRight')
									.click( function() {_this.nextMiniItem(); })
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

	launch: function() {
		this.nextItem();
	},

	nextItem: function() {},

	prevItem: function() {},
	
	showItem: function(item) {},

	loadFancyBox: function(){
		if (this.options.fancyBoxImage){
			if (jQuery('.openPicOnFancyBox').fancybox){
				var _this = this;
				jQuery('.openPicOnFancyBox').fancybox({
					'onStart'	: function () {_this.stopEffect(_this.timer)},
					'onClosed'	: function () {_this.startEffect(_this)}
				});
			}
			else{
				console.log("Fancybox don't load");
			}
		}
	},

	stopEffect: function(timer) {
		clearTimeout(timer);
	},

	startEffect: function(obj) {
		obj.timer = setTimeout(function() { obj.launch() },obj.options.timerDuration);
	},
}

jQuery.fn.inGallery = function(effect, options) {
	inouit.gallery.image.initialize(this, effect, options);
}