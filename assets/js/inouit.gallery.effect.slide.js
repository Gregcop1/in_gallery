inouit.gallery.effect.slide = jQuery.extend(true,{},inouit.gallery.effect.defaults);
jQuery.extend(true,inouit.gallery.effect.slide, {
	name: 'slide',
	itemW: 0,
	nbItem: 0,
	currentItem: -1,
	itemList: '',
	arrayItemList: new Array(),

	initialize: function() {
		this.options = jQuery.extend(true,{
		},this.options);

		this.buildContainer();
		this.placeImage();
		this.buildArrows();
		this.buildAutoStart();
		this.loadFancyBox();
		this.buildThumbnailList();
		this.activeOver();
		this.launch();
	},

	buildContainer: function(){
		this.itemW = this.container.children('.item').first().width();
		this.nbItem = this.container.children('.item').length;
		this.container.addClass('containerGallery')
					.addClass('containerSlide');
		this.container.children('.titleAlbum').hide();
		this.container.children('.item').wrapAll(jQuery('<div />').addClass('itemList'));
		this.itemList = this.container.children('.itemList');
		this.itemList.css({ width: (this.itemW*(this.nbItem+1))+'px' })
		this.itemList.children('.item').css({ width: this.itemW+'px' });
		this.itemList.children('.item').children('.label').hide();
		this.arrayItemList = new Array();
		var _this = this;
		this.itemList.children('.item').each(function(n,i) {
			//jQuery(this).addClass('itemListNb_'+n);
			_this.arrayItemList.push(this);
		});
	},

	nextItem: function() {
		clearTimeout(this.timerImage);
		jQuery(this.arrayItemList[this.currentItem]).removeClass('current');
		this.currentItem++;
		jQuery(this.arrayItemList[this.currentItem]).addClass('current');
		var stop = false;
		if(this.currentItem >= this.nbItem) {
			if(this.options.loop){
				this.currentItem = 0;
			}else {
				stop = true;
			}
		}

		if(thumbnailList){
			var classes = jQuery(this.arrayItemList[this.currentItem]).children('a').attr('class').split(/\s+/);
			thumbnailList.refreshActiveThumbnail( String(classes[0]).replace('itemPic_','') );
		}
			
		if(!stop){
			this.itemList.animate({ marginLeft: -(this.itemW*this.currentItem)+'px' }, this.options.effectDuration);

			if (this.options.autoStart) {
				var _this = this;
				this.timerImage = setTimeout(function() { _this.nextItem() },this.options.timerDuration);
			}
		}
	},

	prevItem: function() {
		clearTimeout(this.timerImage);
		jQuery(this.arrayItemList[this.currentItem]).removeClass('current');
		this.currentItem--;
		jQuery(this.arrayItemList[this.currentItem]).addClass('current');
		var stop = false;
		if(this.currentItem < 0) {
			if(this.options.loop){
				this.currentItem = this.nbItem-1;
			}else {
				stop = true;
			}
		}

		if(thumbnailList){
			var classes = jQuery(this.arrayItemList[this.currentItem]).children('a').attr('class').split(/\s+/);
			thumbnailList.refreshActiveThumbnail( String(classes[0]).replace('itemPic_','') );
		}
			
		if(!stop){
			this.itemList.animate({ marginLeft: -(this.itemW*this.currentItem)+'px' }, this.options.effectDuration);

			if (this.options.autoStart) {
				var _this = this;
				this.timerImage = setTimeout(function() { _this.nextItem() },this.options.timerDuration);			
			}
		}
	},

	showItem: function(item) {
		clearTimeout(this.timerImage);
		jQuery(this.arrayItemList[this.currentItem]).removeClass('current');
		var className = item.attr('class');
		className = className.split(' ');
		var nbrMiniPic = className[1];
		className = className[0];
		className = className.replace('miniItemListNb_','');
		this.currentItem = className;
		jQuery(this.arrayItemList[this.currentItem]).addClass('current');
		var stop = false;
		if(this.currentItem >= this.nbItem) {
			if(this.options.loop){
				this.currentItem = 0;
			}else {
				stop = true;
			}
		}

		nbrMiniPic = nbrMiniPic.replace('itemMiniPic_','');
		
		if(thumbnailList){
			thumbnailList.refreshActiveThumbnail(nbrMiniPic);
		}
		
		if(!stop){
			this.itemList.animate({ marginLeft: -(this.itemW*this.currentItem)+'px' }, this.options.effectDuration);

			if (this.options.autoStart) {
				var _this = this;
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
				_this.itemList.children('.item').children('.label').fadeIn();
			},
			function(){
				_this.container.children('.titleAlbum').fadeOut('slow');
				_this.container.children('.arrowsContainer').fadeOut('slow');
				_this.container.children('.autoStartContainer').fadeOut('slow');		
				_this.itemList.children('.item').not('.current').children('.label').hide();
				_this.itemList.children('.current').children('.label').fadeOut();
			}
		);
	}
});