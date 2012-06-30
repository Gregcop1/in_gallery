inouit.gallery.effect.slide = jQuery.extend(true,{},inouit.gallery.effect.default);
jQuery.extend(true,inouit.gallery.effect.slide, {
	name: 'slide',
	itemW: 0,
	nbItem: 0,
	currentItem: -1,
	itemList: '',

	initialize: function() {
		this.options = jQuery.extend(true,{
		},this.options);

		this.buildContainer();
		this.placeImage();
		this.buildArrows();
		this.launch();
	},

	buildContainer: function(){
		this.itemW = this.container.children('.item').first().width();
		this.nbItem = this.container.children('.item').length;
		this.container.addClass('containerGallery')
					.addClass('containerSlide');
		this.container.children('.item').wrapAll(jQuery('<div />').addClass('itemList'));
		this.itemList = this.container.children('.itemList');
		this.itemList.css({ width: (this.itemW*(this.nbItem+1))+'px' })
		this.itemList.children('.item').css({ width: this.itemW+'px' });
	},

	nextItem: function() {
		clearTimeout(this.timer);
		this.currentItem++;
		var stop = false;
		if(this.currentItem >= this.nbItem) {
			if(this.options.loop){
				this.currentItem = 0;
			}else {
				stop = true;
			}
		}

		if(!stop){
			this.itemList.animate({ marginLeft: -(this.itemW*this.currentItem)+'px' }, this.options.effectDuration);

			var _this = this;
			this.timer = setTimeout(function() { _this.nextItem() },this.options.timerDuration);
		}
	},

	prevItem: function() {
		clearTimeout(this.timer);
		this.currentItem--;
		var stop = false;
		if(this.currentItem < 0) {
			if(this.options.loop){
				this.currentItem = this.nbItem-1;
			}else {
				stop = true;
			}
		}

		if(!stop){
			this.itemList.animate({ marginLeft: -(this.itemW*this.currentItem)+'px' }, this.options.effectDuration);
		}
	},
});