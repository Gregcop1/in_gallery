inouit.gallery.effect.fade = jQuery.extend(true,{},inouit.gallery.effect.default);
jQuery.extend(true,inouit.gallery.effect.fade, {
	name: 'fade',

	initialize: function() {
		this.options = jQuery.extend(true,{
		},this.options);

		this.buildContainer();
		this.placeImage();
		this.buildArrows();
		this.buildMiniList();
		this.buildMiniArrows();
		this.loadFancyBox();
		this.launch();
	},

	buildContainer: function(){
		this.container.addClass('containerGallery')
					.addClass('containerFade');
		this.container.children('.item').fadeOut(0);
	},

	nextItem: function() {

		clearTimeout(this.timer);
		var nextItem = '';

		if(!this.currentItem){
			nextItem = this.container.children('.item').first();
		}else {
			nextItem = this.currentItem.next('.item');
			if(!nextItem.length && this.options.loop){
				nextItem = this.container.children('.item').first();
			}
		}
		if(nextItem.length) {
			if(this.currentItem) {
				this.currentItem.fadeOut(this.options.effectDuration);
			}
		
			nextItem.fadeIn(this.options.effectDuration);
			this.currentItem = nextItem;
			
			var _this = this;
			this.timer = setTimeout(function() { _this.nextItem() },this.options.timerDuration);
		}
	},

	prevItem: function() {
		clearTimeout(this.timer);
		var prevItem = '';

		if(this.currentItem) {
			prevItem = this.currentItem.prev('.item');
			if(!prevItem.length && this.options.loop){
				prevItem = this.container.children('.item').last();
			}
		}

		this.goNextItem(nextItem);
	},

	showItem: function(item,timer) {
		clearTimeout(timer);
		var nbrMiniPic = item.attr('class');
		nbrMiniPic = nbrMiniPic.replace('itemMiniPic_','');
		var nextItem = jQuery('.itemPic_'+nbrMiniPic).parent('.item');
		this.goNextItem(nextItem);
	},

	goNextItem: function (nextItem) {
		if(nextItem.length) {
			if(this.currentItem) {
				this.currentItem.fadeOut(this.options.effectDuration);
			}
		
			nextItem.fadeIn(this.options.effectDuration);
			this.currentItem = nextItem;
			
			var _this = this;
			this.timer = setTimeout(function() { _this.nextItem() },this.options.timerDuration);
		}		
	},
});