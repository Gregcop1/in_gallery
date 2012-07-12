if(!inouit) {
	var inouit = {};
}

if(!inouit.gallery) {
	inouit.gallery = {};
}

inouit.gallery.imageNavigation = {

	initialize: function(container, imageEffect, navEffect, options){
		if(navEffect) {
			var navEffect = jQuery.extend(true,{},navEffect);
		
			navEffect.options = jQuery.extend(true,navEffect.options, options);
			navEffect.container = container;
			navEffect.api = this;
			
			if (imageEffect) {
				navEffect.imageEffect = imageEffect;
			}

			navEffect.initialize();
		}else {
			console.log('You must choose an existing navEffect')
		}
	},
};

if(!inouit.gallery.navEffect){
	inouit.gallery.navEffect = {}
}
inouit.gallery.navEffect.default = {
	name: 'default',
	api: '',
	container: '',
	options: {
	},

	initialize: function(){
		this.buildMiniList();
		this.buildMiniArrows();
	},

	buildMiniList: function(){},

	buildMiniArrows: function(){},

	bindMiniPicture: function (){},

	unBindMiniPicture: function (){},
}

jQuery.fn.inGalleryNavigation = function(imageEffect, navEffect, options) {
	inouit.gallery.imageNavigation.initialize(this, imageEffect, navEffect, options);
}