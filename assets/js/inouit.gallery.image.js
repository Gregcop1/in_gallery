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
			containerImage = container;
			effect.api = this;
			
			effect.initialize();
		}else {
			alert('You must choose an existing effect');
		}
	}
};


if(!inouit.gallery.effect){
	inouit.gallery.effect = {};
}

inouit.gallery.effect.defaults = {
	name: 'defaults',
	api: '',
	container: '',
	options: {
		timerDuration: 3000,
		effectDuration: 500,
		loop: true,
		fancyBoxImage: false,
		enableThumbnailList: true,
		autoStart: false
	},
	thumbnailList: '',
	timer: '',

	timerImage:  '',
	containerImage: '',
	currentItem: '',
	prevItem: '',
	nextItem: '',

	initialize: function(){
		this.buildContainer();
		this.placeImage();
		this.buildArrows();
		this.buildAutoStart();
		this.loadFancyBox();
		this.buildThumbnailList();
		this.activeOver();
		this.launch();
	},

	getContainer: function () { return containerImage},

	buildContainer: function(){},

	placeImage: function(){
		var cHeight = this.container.height();
		var objPicture = this.container.children('.item').children('img').not('.thumbnail');
		if (objPicture.length == 0){
			objPicture = this.container.children('.item').children('a').children('img').not('.thumbnail');
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
										.appendTo(this.container).hide();
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

	buildAutoStart: function(){
		if(this.container.find('.item').length){
			var autoStart = jQuery('<div/>').addClass('autoStartContainer')
										.appendTo(this.container).hide();

			var _this = this;
			if(this.options.autoStart) {
				var start = jQuery('<a/>').attr('href','javascript:;')
									.addClass('autoStart')
									.addClass('autoStartLaunch')
									.click( function() {_this.autoStartLaunch(); })
									.appendTo(autoStart).hide();
			
				var stop = jQuery('<a/>').attr('href','javascript:;')
									.addClass('autoStart')
									.addClass('autoStartStop')
									.click( function() {_this.autoStartStop(); })
									.appendTo(autoStart).show();
			} else {
				var start = jQuery('<a/>').attr('href','javascript:;')
									.addClass('autoStart')
									.addClass('autoStartLaunch')
									.click( function() {_this.autoStartLaunch(); })
									.appendTo(autoStart).show();
			
				var stop = jQuery('<a/>').attr('href','javascript:;')
									.addClass('autoStart')
									.addClass('autoStartStop')
									.click( function() {_this.autoStartStop(); })
									.appendTo(autoStart).hide();
			}
		}
	},

	launch: function() {
		this.nextItem();
	},

	nextItem: function() {},

	prevItem: function() {},
	
	showItem: function(item) {},
	
	autoStartLaunch: function() {
		this.options.autoStart = true;
		jQuery('.autoStartLaunch').hide();
		jQuery('.autoStartStop').show();
		this.nextItem();
	},
	
	autoStartStop: function() {
		this.options.autoStart = false;
		jQuery('.autoStartStop').hide();
		jQuery('.autoStartLaunch').show();
		this.stopEffect();
	},

	buildThumbnailList: function() {
		if (this.options.enableThumbnailList){
			thumbnailList = jQuery.extend(true,{},inouit.gallery.thumbnailList);
			thumbnailList.gallery = this;
			thumbnailList.initialize();
		}
	},

	loadFancyBox: function(){
		if (this.options.fancyBoxImage){
			if (jQuery('.openPicOnFancyBox').fancybox){
				var _this = this;
				jQuery('.openPicOnFancyBox').fancybox({
					'onStart'	: function () {_this.stopEffect()},
					'onClosed'	: function () {_this.startEffect(_this)}
				});
			}
			else{
				alert("Fancybox don't load");
			}
		}
	},

	stopEffect: function() {
		clearTimeout(this.timerImage);
	},

	startEffect: function(obj) {
		this.timerImage = setTimeout(function() { obj.launch() },obj.options.timerDuration);
	},

	activeOver: function() {}
}

jQuery.fn.inGallery = function(effect, options) {
	inouit.gallery.image.initialize(this, effect, options);
}