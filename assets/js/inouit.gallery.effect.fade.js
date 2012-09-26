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
			this.buildAutoStart();
			this.loadFancyBox();
			this.buildThumbnailList();
			this.activeOver();
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
		this.container.children('.item').children('.label').hide();
		this.container.children('.titleAlbum').hide();
	},

	prepareForMobile: function() {
		this.container.addClass('containerGalleryForMobile');

		this.container.children('.item').wrapAll(jQuery('<div/>').addClass('mobileFlexibleContainer'));

		var _this = this;
		jQuery(window).resize( function() {
			var widthTotal = 0;
			var mobileFlexibleContainer = _this.container.children('.mobileFlexibleContainer');
			var objMiniPicture = mobileFlexibleContainer.children('.item');
			objMiniPicture.find('img').not('.thumbnail').css('maxWidth',(_this.container.width()-30)+'px')
			objMiniPicture.each(function(){
				widthTotal = widthTotal + jQuery(this).find('img').not('.thumbnail').width();
			});

			mobileFlexibleContainer.width((widthTotal+100)+'px');
		} );
		jQuery(window).trigger('resize');
	},

	refreshSizeForMobile: function() {

		
	},

	nextItem: function() {

		clearTimeout(this.timerImage);
		if(!this.container){
			this.container = this.getContainer();
		}
		if(!this.currentItem){
			nextItem = this.container.children('.item').first();
		}else{
			nextItem = this.currentItem.next('.item');
			if(!nextItem.length && this.options.loop){
				nextItem = this.currentItem.parent().children('.item').first();
			}
		}
		this.goNextItem(nextItem);
	},

	prevItem: function() {
		clearTimeout(this.timerImage);
		var prevItem = '';

		if(this.currentItem) {
			prevItem = this.currentItem.prev('.item');
			if(!prevItem.length && this.options.loop){
				prevItem = this.container.children('.item').last();
			}
		}

		if(prevItem.length) {
			if(this.currentItem) {
				this.currentItem.removeClass('current');
				this.currentItem.fadeOut(this.options.effectDuration);
			}

			prevItem.fadeIn(this.options.effectDuration);
			this.currentItem = prevItem;
			this.currentItem.addClass('current');

			if(thumbnailList){
				var classes = prevItem.children('a').attr('class').split(/\s+/);
				thumbnailList.refreshActiveThumbnail( String(classes[0]).replace('itemPic_','') );
			}

			var _this = this;
			if (this.options.autoStart) {
				this.timerImage = setTimeout(function() { _this.nextItem() },this.options.timerDuration);
			}
		}
	},

	showItem: function(item) {
		clearTimeout(this.timerImage);
		var nbrMiniPic = item.attr('class');
		nbrMiniPic = nbrMiniPic.replace('itemMiniPic_','');
		nextItem = jQuery('.itemPic_'+nbrMiniPic).parent('.item');
		
		if(thumbnailList){
			thumbnailList.refreshActiveThumbnail(nbrMiniPic);
		}

		this.goNextItem();
	},

	goNextItem: function () {
		if(nextItem.length) {
			if(this.currentItem) {
				this.currentItem.removeClass('current');
				this.currentItem.fadeOut(this.options.effectDuration);
			}
			this.currentItem = nextItem;
			nextItem.fadeIn(this.options.effectDuration);
			nextItem.addClass('current');
			
			if(thumbnailList){
				var classes = nextItem.children('a').attr('class').split(/\s+/);
				thumbnailList.refreshActiveThumbnail( String(classes[0]).replace('itemPic_','') );
			}
			
			var _this = this;
			if (this.options.autoStart) {
				this.timerImage = setTimeout(function() { _this.nextItem() },this.options.timerDuration);
			}
		}		
	},

	activeOver: function() {
		var _this = this;
		this.container.hover(
			function(){
				_this.container.children('.titleAlbum').fadeIn('slow');
				_this.container.children('.arrowsContainer').show('slow');
				_this.container.children('.autoStartContainer').show('slow');
				_this.container.children('.item').children('.label').fadeIn();
			},
			function(){
				_this.container.children('.titleAlbum').fadeOut('slow');
				_this.container.children('.arrowsContainer').hide('slow');
				_this.container.children('.autoStartContainer').hide('slow');		
				_this.container.children('.item').not('.current').children('.label').hide();
				_this.currentItem.children('.label').fadeOut();
			}
		);
	}

});