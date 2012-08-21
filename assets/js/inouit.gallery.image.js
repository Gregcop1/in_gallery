timerImage = '';
containerImage='';
currentItem='';
prevItem='';
nextItem='';
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
			alert('You must choose an existing effect')
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
		fancyBoxImage: false,
	},
	timer: '',

	initialize: function(){
		this.buildContainer();
		this.placeImage();
		this.buildArrows();
		this.loadFancyBox();
		this.launch();
	},

	getContainer: function () { return containerImage},

	buildContainer: function(){},

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
		clearTimeout(timerImage);
	},

	startEffect: function(obj) {
		timerImage = setTimeout(function() { obj.launch() },obj.options.timerDuration);
	},
}

jQuery.fn.inGallery = function(effect, options) {
	inouit.gallery.image.initialize(this, effect, options);
}