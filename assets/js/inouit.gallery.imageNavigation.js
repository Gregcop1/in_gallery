if(!inouit) {
	var inouit = {};
}

if(!inouit.gallery) {
	inouit.gallery = {};
}

inouit.gallery.imageNavigation = {
	navigationEffect: '',

	initialize: function(container, imageEffect, navEffect, options){
		if(navEffect) {
			navigationEffect = jQuery.extend(true,{},navEffect);
		
			navigationEffect.options = jQuery.extend(true,navigationEffect.options, options);
			navigationEffect.container = container;
			navigationEffect.api = this;
			
			if (imageEffect) {
				navigationEffect.imageEffect = imageEffect;
			}

			navigationEffect.initialize();
		}else {
			alert('You must choose an existing navEffect')
		}
	}
};

if(!inouit.gallery.navEffect){
	inouit.gallery.navEffect = {};
}

inouit.gallery.navEffect.defaults = {
	name: 'defaults',
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

	refreshMaxWidth: function(){},
}

jQuery.fn.inGalleryNavigation = function(imageEffect, navEffect, options) {
	inouit.gallery.imageNavigation.initialize(this, imageEffect, navEffect, options);
}