if(!inouit) {
	var inouit = {};
}

if(!inouit.gallery) {
	inouit.gallery = {};
}

inouit.gallery.image = {
	options: {
		timerDuration: 3000,
		loop: true,
	},
	container: '',
	effect: '',

	initialize: function(container, options){
		if(options && options.effect) {
			this.effect = options.effect;
		}else {
			this.effect = inouit.gallery.effect;
		}
		
		this.container = container;
		this.options = jQuery.extend(this.options, options);
		this.effect.api = this;
		
		this.effect.initialize();
	},
};

inouit.gallery.effect = {
	api: '',
	currentItem: 0,
	timerMax: 0,
	timer: '',

	initialize: function(){
		this.buildContainer();
		this.placeImage();
		this.buildArrows();
		this.launch();
	},

	buildContainer: function(){},

	placeImage: function(){
		var cHeight = this.api.container.height();
		this.api.container.children('.item').children('img').each(function(){
			var iHeight = jQuery(this).attr('height');
			if(cHeight > iHeight){
				jQuery(this).css({ 'margin-top': ((cHeight-iHeight)/2)+'px' })
			}
		});
	},

	buildArrows: function(){
		var cont = this.api.container;
		var arrows = jQuery('<div/>').addClass('arrowsContainer')
									.appendTo(cont);

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

		
	},

	launch: function() {
		this.nextItem();
	},

	nextItem: function() {console.log('coucou')},

	prevItem: function() {},
}


inouit.gallery.fade = jQuery.extend(inouit.gallery.effect, {
	api: '',
	currentItem: 0,
	timerMax: 0,
	timer: '',

	initialize: function() {
		this.api.options = jQuery.extend({
			fadeDuration: 500
		},this.api.options);

		this.buildContainer();
		this.placeImage();
		this.buildArrows();
		this.launch();
	},

	buildContainer: function(){
		this.api.container.addClass('containerFade');
		this.api.container.children('.item').fadeOut(0);
		this.timerCount = this.api.container.children('.item').length;
	},

	nextItem: function() {
		clearTimeout(this.timer);
		var nextItem = '';

		if(!this.currentItem){
			nextItem = this.api.container.children('.item').first();
		}else {
			nextItem = this.currentItem.next('.item');
			if(!nextItem.html() && this.api.options.loop){
				nextItem = this.api.container.children('.item').first();
			}
		}

		if(nextItem.html()) {
			if(this.currentItem) {
				this.currentItem.fadeOut(this.api.options.fadeDuration);
			}
		
			nextItem.fadeIn(this.api.options.fadeDuration);
			this.currentItem = nextItem;
			
			var _this = this;
			this.timer = setTimeout(function() { _this.nextItem() },this.api.options.timerDuration);
		}
	},

	prevItem: function() {
		clearTimeout(this.timer);
		var prevItem = '';

		if(this.currentItem) {
			prevItem = this.currentItem.prev('.item');
			if(!prevItem.html() && this.api.options.loop){
				prevItem = this.api.container.children('.item').last();
			}
		}

		if(prevItem.html()) {
			if(this.currentItem) {
				this.currentItem.fadeOut(this.api.options.fadeDuration);
			}
		
			prevItem.fadeIn(this.api.options.fadeDuration);
			this.currentItem = prevItem;
		}
	},
});

$.fn.inGallery = function(options) {
	inouit.gallery.image.initialize(this,options);
}

//@TODO à supprimer
jQuery(document).ready(function() {
	jQuery('#imageList').inGallery({effect: inouit.gallery.fade});
});