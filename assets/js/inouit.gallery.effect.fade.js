inouit.gallery.effect.fade = jQuery.extend(true,{},inouit.gallery.effect.defaults);
jQuery.extend(true,inouit.gallery.effect.fade, {
	name: 'fade',
	timer: '',

	initialize: function() {
		this.options = jQuery.extend(true,{
		},this.options);

		this.buildContainer();
		if(!this.container.hasClass('containerGalleryForMobile')) {
			this.placeImage();
			this.buildArrows();
			this.loadFancyBox();
			this.launch();
		}
	},

	buildContainer: function(){
		this.container.addClass('containerGallery')
					.addClass('containerFade');
		
		if(navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i)){
			this.prepareForMobile();
		}else  {
			this.container.children('.item').fadeOut(0);
		}
	},

	prepareForMobile: function() {
		this.container.addClass('containerGalleryForMobile');

		this.container.children('.item').wrapAll(jQuery('<div/>').addClass('mobileFlexibleContainer'));

		var _this = this;
		jQuery(window).resize( function() {
			var widthTotal = 0;
			var mobileFlexibleContainer = _this.container.children('.mobileFlexibleContainer');
			var objMiniPicture = mobileFlexibleContainer.children('.item');
			objMiniPicture.find('img').css('maxWidth',_this.container.width()+'px')
			objMiniPicture.each(function(){
				widthTotal = widthTotal + jQuery(this).find('img').width();
			});

			mobileFlexibleContainer.width((widthTotal+100)+'px');
		} );
		jQuery(window).trigger('resize');
	},

	refreshSizeForMobile: function() {

		
	},

	nextItem: function() {

		clearTimeout(timerImage);
		if(!this.container){
			this.container = this.getContainer();
		}
		if(!currentItem){
			nextItem = this.container.children('.item').first();
		}else{
			nextItem = currentItem.next('.item');
			if(!nextItem.length && this.options.loop){
				nextItem = currentItem.parent().children('.item').first();
			}
		}
		this.goNextItem(nextItem);
	},

	prevItem: function() {
		clearTimeout(timerImage);
		var prevItem = '';

		if(currentItem) {
			prevItem = currentItem.prev('.item');
			if(!prevItem.length && this.options.loop){
				prevItem = this.container.children('.item').last();
			}
		}

		if(prevItem.length) {
			if(currentItem) {
				currentItem.fadeOut(this.options.effectDuration);
			}

			prevItem.fadeIn(this.options.effectDuration);
			currentItem = prevItem;

			var _this = this;
			timerImage = setTimeout(function() { _this.nextItem() },this.options.timerDuration);
		}
	},

	showItem: function(item) {
		clearTimeout(timerImage);
		var nbrMiniPic = item.attr('class');
		nbrMiniPic = nbrMiniPic.replace('itemMiniPic_','');
		nextItem = jQuery('.itemPic_'+nbrMiniPic).parent('.item');
		this.goNextItem();
	},

	goNextItem: function () {
		if(nextItem.length) {
			if(currentItem) {
				currentItem.fadeOut(this.options.effectDuration);
			}
			currentItem = nextItem;
			nextItem.fadeIn(this.options.effectDuration);
			
			var _this = this;
			timerImage = setTimeout(function() { _this.nextItem() },this.options.timerDuration);
		}		
	},
});