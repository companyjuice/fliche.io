(function($){
	
	"use strict";

	window.wprotoFrontCore = {
	
		/**
			Constructor
		**/
		initialize: function() {

			var self = this;

			$(document).ready(function(){
				self.build();
				self.events();
			});

		},
		/**
			Build page elements, plugins init
		**/
		build: function() {
			
			var self = this;
		
			// Init preloader
			this.initPreloader();
		
			// Add body classes
			this.addBodyClasses();
			
			// GoTop script
			this.initGoTop();
			
			// Tooltips
			this.initToolTips();
			
			// SVG Images
			this.initSVG();
			
			// Theme header menus
			this.initMenus();
			
			// Init custom styles for forms
			this.initForms();
			
			// Responsive videos
			this.responsiveVideos();
			
			// WP Tweaks
			this.wpTweaks();
			
			// Validate comments form
			this.validateCommentsForm();
			
			// initGalleries
			this.initGalleries();
			
			// init Tabs
			this.initTabs();
			this.initResponsiveTabs();
			
			// Equal Height
			this.initEqualHeight();
			
			// init Lightboxes
			this.initLightbox();
			
			// init Testimonials
			this.initTestimonials();
			
			// init Carousels
			this.initCarousels();
			
			// posts Carousels
			this.initPostsCarousels();
			
			// products Carousels
			this.initProductsCarousels();
			
			// init LatestTweets
			this.initLatestTweets();
			
			// login / register shortcode
			this.initLoginRegister();
			
			// WooCommerce events
			this.initWooEvents();
			
			// Additional post layouts
			this.initMasonry();
			
			// init Hovers Direction
			this.initHoverDir();
			
		},
		/**
			Set page events
		**/
		events: function() {
			
			var self = this;
			
			// Init Responsive Tabs
			$( window ).resize(function() {
				self.initResponsiveTabs();
				
				if( $(window).width() > 995 ) {
					$('#header-menu').not('.wproto-hidden').show();
					$('#wproto-mobile-menu').hide();
				} else {
					$('#header-menu').not('.wproto-hidden').hide();
					if( ! $('#primary-nav-menu').hasClass('wproto-search-displayed') ) {
						$('#wproto-mobile-menu').css('display', 'inline-block');
					}
				}
				
			});
			
			// page unloader
			if( $('body').hasClass('unloader') && $(window).width() > 959 && $('body').hasClass('mobile') == false ) {
				window.onbeforeunload = function(){
					var unloaderSpeed = $('#unloader').data('unload-speed')
					var loader = new SVGLoader( document.getElementById( 'unloader' ), { speedIn : unloaderSpeed } );
					loader.show();
				};
			}
			
			// flickr widget hover
			$('.widget_flickr .flickr_badge_image a').hover( function() {
				$(this).parent().parent().find('.flickr_badge_image').addClass('hover');
				$(this).addClass('hovered');
			}, function() {
				$(this).parent().parent().find('.flickr_badge_image').removeClass('hover');
				$(this).removeClass('hovered');
			});
			
			// Wproto AJAX pagination button clicked
			$('body').on( 'click', '.wproto-load-more-posts-link', function() {
				var $link = $(this);
				
				if( $link.attr('disabled') == 'disabled' ) return false;
				
				var $target = $( $link.data('ajax-target') );
				var data = $link.data();		
				var appendType = $link.data('append-type');
								
				$.ajax({
					url: wprotoEngineVars.ajaxurl,
					type: "POST",
					dataType : 'json',
					data: {
						'action' : 'wproto_ajax_pagination',
						'data' : data
					},
					beforeSend: function() {
						$link.attr('disabled', 'disabled');
						$target.fadeTo( 300, '0.5' );
						$link.text( $link.data('loading-text') );
					},
					success: function( response ) {
						
						$link.data( 'current-page', response.current_page );
						$link.data( 'next-page', response.next_page );
				
						if( response.next_page > $link.data('max-pages') || self.stringToBoolean( response.hide_link ) ) {
							$link.parents('.wproto-pagination').remove();
						}
						
						if( response.html ) {
							
							if( appendType == 'grid' ) {
								
								$target.append( response.html );
								
								self.responsiveVideos();
								self.initGalleries();
								self.initLightbox();
								self.initCarousels();
								self.initProductsCarousels();
								
								
							} else if( appendType == 'masonry' ) {
								
								$target.append( response.html );
								
								self.responsiveVideos();
								self.initGalleries();
								self.initLightbox();
								self.initCarousels();
								
								self.initMasonry();
								self.initHoverDir();
								
								$target.waitForImages({
									waitForAll: true,
									finished: function() {
										self.initMasonry();
										self.initHoverDir();
									}
								});
								
							} 
							
						}
						
						$link.removeAttr('disabled');
						$target.fadeTo( 300, '1' );
						$link.text( $link.data('normal-text') );
						
					},
					error: function() {
						$link.removeAttr('disabled');
						$target.fadeTo( 300, '1' );
						$link.text( $link.data('normal-text') );
						self.alertMessage( wprotoEngineVars.strServerResponseError );
					},
					ajaxError: function() {
						$link.removeAttr('disabled');
						$target.fadeTo( 300, '1' );
						$link.text( $link.data('normal-text') );
						self.alertMessage( wprotoEngineVars.strAJAXError );
					}
				});
				
				return false;
			});
			
			// TopBar login / register
			$('#wproto-top-signin').click( function() {
				
				var $href = $(this);
				
				var top = $('#top-bar').outerHeight();
				var left = $href.offset().left;
				
				var $widget = $('#wproto-top-bar-login-register-widget');
				
				$widget.css('top', top + 'px' ).css('left', left - $widget.outerWidth() + $href.width() + 'px' );
				
				$widget.slideToggle( 400, function() {
					$href.find('i.caret').toggleClass('fa-caret-down').toggleClass('fa-caret-up');
					$href.parent().toggleClass('active');
				});
				
				return false;
			});
			
			// TopBar WishList
			$('#wproto-top-wishlist').click( function() {
				
				var $href = $(this);
				
				var top = $('#top-bar').outerHeight();
				var left = $href.offset().left;
				
				var $widget = $('#wproto-top-bar-wishlist-widget');
				
				$widget.css('top', top + 'px' ).css('left', left - $widget.outerWidth() + $href.width() + 'px' );
				
				$widget.slideToggle( 400, function() {
					$href.find('i.caret').toggleClass('fa-caret-down').toggleClass('fa-caret-up');
					$href.parent().toggleClass('active');
				});
				
				return false;
			});
			
			// add to wishlist
			$('.wproto-add-to-wishlist').click( function() {
				
				var $link = $(this);
				
				var wishlistElems = $.cookie( 'wpl_wproto_wishlist' );
				var addProductId = $link.data('id');
				
				if( wishlistElems == undefined ) {
					wishlistElems = [];
				} else {
					wishlistElems = JSON.parse( wishlistElems );
				}
				
				wishlistElems.push( addProductId );
				
				$.cookie('wpl_wproto_wishlist', JSON.stringify( $.unique( wishlistElems ) ), { expires: 365, path: '/' });
				
				self.refreshWishlistNum();
				
				if( $link.hasClass('iconic-wishlist') ) {
					$link.addClass('active');
					$link.removeAttr('title');
					
					if( $link.hasClass('with-text') ) {
						$link.html('');
					}
					
				} else {
					$link.html( wprotoEngineVars.strAddedToWishlist ).fadeOut( 1200 );
				}
				
				self.initToolTips();
				
				return false;
			});
			
			// remove from WishList
			$('#wproto-top-bar-wishlist-widget').on('click', 'a.wproto-remove', function() {
				
				var wishlistElems = $.cookie( 'wpl_wproto_wishlist' );
				var addProductId = $(this).data('id');
				var $item = $(this).parent();
				
				if( wishlistElems == undefined ) {
					wishlistElems = [];
				} else {
					wishlistElems = JSON.parse( wishlistElems );
				}
				
				var i = wishlistElems.indexOf( addProductId );
				if(i != -1) {
					wishlistElems.splice(i, 1);
				}
				
				$.cookie('wpl_wproto_wishlist', JSON.stringify( $.unique( wishlistElems ) ), { expires: 365, path: '/' });
				
				$item.fadeOut( 500, function() {
					$(this).remove();
				});
				
				if( wishlistElems.length == 0 ) {
					$('#wproto-top-bar-wishlist-widget').html('<p class="empty">'+wprotoEngineVars.strEmptyWishlist+'</p>');
				}
				
				self.refreshWishlistNum();
				
				return false;
			});
			
			// clear wishlist
			$('#wproto-clear-wishlist').click( function() {
				$.removeCookie( 'wpl_wproto_wishlist', { expires: 365, path: '/' } );
				self.refreshWishlistNum();
				return false;
			});
			
			// toggle mobile menus
			$('#wproto-mobile-menu').click( function() {
				
				$('#header-menu').slideToggle();
				
				return false;
			});
			
			// header search link
			$('#wproto-header-search-link').click( function() {
				
				$('#header-menu').toggleClass('wproto-hidden');
				$('#primary-nav-menu').toggleClass('wproto-search-displayed');
				
				var $link = $(this);
				$link.toggleClass('cross');
				
				if( $link.hasClass('cross') ) {
					$('#wproto-header-cart-link, #header-menu').hide();
					$('#wproto-header-search .form-input').focus();
					
					if( $(window).width() < 995 ) {
						$('#wproto-mobile-menu').hide();
					}
				} else {
					$('#wproto-header-cart-link').show();
					
					if( $(window).width() > 995 ) {
						$('#header-menu').show();
					} else {
						$('#wproto-mobile-menu').css('display', 'inline-block');
					}
					
				}
				
				return false;
			});
			
			// refresh custom cart
			$('#content').on( 'click', '.add_to_cart_button, .single_add_to_cart_button', function() {
				
				var $headerCart = $('#wproto-header-cart-link');
				
				if( $headerCart.length ) {
					setTimeout(function() {
						var $totals = $headerCart.find('span');
						
						$.ajax({
							url: wprotoEngineVars.ajaxurl,
							type: "POST",
							data: {
								'action' : 'wproto_get_woocommerce_totals'
							},
							success: function( response ) {
								$totals.html( response );
							}
						});
						
					}, 1000);
				}
				
			});
			
			// header cart link, display header cart
			$('#wproto-header-cart-link').click( function() {
				
				var $header = $('#header');
				
				var $link = $(this);
				var loadingText = $link.data('loading-text');
				var $cart = $('#wproto-header-cart-widget');
				
				var top = $header.outerHeight();
				var left = $link.position().left;
				$cart.css('top', top + 'px' ).css('left', left - $cart.outerWidth() + $link.width() + 15 + 'px' );
				
				$cart.toggleClass('opened');
				
				$cart.fadeToggle();
				
				return false;
			});
			
			// menu scrolling
			if( $('#header').hasClass('style-slider') == false && $('body').hasClass('no-scrolling-menu') == false && $('#header').length ) {
				
				var el = $('#header');
				var elpos_original = el.offset().top;
		
				$(window).scroll(function(){
					var elpos = el.offset().top;
 					var windowpos = $(window).scrollTop();
  				var finaldestination = windowpos;
  				var padding = el.outerHeight();
  				var elContainer = $('#menu-header-container');
  	
  				if( ($(window).width() > 959) || ( $(window).width() < 959 && $('body').hasClass('menu-mobile-scroll') == true ) ) {
  					
  					if(windowpos<=elpos_original) {
   						finaldestination = elpos_original;
   						
     					el.removeClass('scrolled').attr('style', '').addClass('normal-state');
      				elContainer.removeClass('scrolling').css('padding-top', '0');
      				
  					} else {
  						
  						if( elContainer.hasClass('scrolling') == false ) {
  							elContainer.css('padding-top', padding + 'px' );
  						}
  						
  						if( el.hasClass('scrolled') == false ) {
  							
  							el.css('top', '-' + padding + 'px' );
  							
  							if( $('body').hasClass('admin-bar') ) {
  								var mTop = $('#wpadminbar').height();
	  							el.removeClass('normal-state').addClass('scrolled').animate({
	    							top: mTop + "px"
	  							}, 700 );	
  							} else {
	  							el.removeClass('normal-state').addClass('scrolled').animate({
	    							top: "0px"
	  							}, 700 );	
  							}
  							
  						}

 						}
  				} else {
 						el.removeClass('scrolled').addClass('normal-state').attr('style', '');
  				}
				});
				
			}
			
			// one page menu
			if( $('#menu-header-container').hasClass('enable-one-page-menu') ) {
			
				$('#header-menu').find('li.level-0:first').addClass('current-menu-item');
			
				$('#header-menu').onePageNav({
					currentClass: 'current-menu-item',
					changeHash: false,
					scrollSpeed: 750,
					scrollOffset: 100,
					filter: ':not(.external)'
				});
				
			}
			
		},
		/***************************************************************************************************************************************
			Class methods
		***************************************************************************************************************************************/
		/**
			Preloader
		**/
		initPreloader: function() {
			
			var self = this;

			
			// Close preloader
			$(window).load(function() {
				if( $('body.preloader').length ) {
					
					$('body').waitForImages({
						waitForAll: true,
						finished: function() {
							
							$('#wproto-preloader-inner').hide();
							$('#wproto-preloader').fadeOut( 1200, function() {
								$(this).remove();
								$('body').addClass('loaded');
								// init CSS animations
								self.initAnimations();
							});
							
							self.initMasonry();
						
						}
					});
					
					if( $('.ie7, .ie8, .ie9, .ie10').length ) {
						$('#wproto-preloader').remove();
						$('body').removeClass('preloader');
						self.initAnimations();
					}
					
				} else {
					// initAnimations
					$('body').waitForImages({
						waitForAll: true,
						finished: function() {
							self.initMasonry();
						}
					});
					self.initAnimations();
				}
			});
		},
		/**
			Add body classes
		**/
		addBodyClasses: function() {
			/**
				Check for retina displays
			**/
			if( document.cookie.indexOf('device_pixel_ratio') == -1 && 'devicePixelRatio' in window && window.devicePixelRatio == 2 ){

				var date = new Date();
				date.setTime( date.getTime() + 3600000 );

				document.cookie = 'device_pixel_ratio=' + window.devicePixelRatio + ';' +  ' expires=' + date.toUTCString() +'; path=/';
				//if cookies are not blocked, reload the page
				if(document.cookie.indexOf('device_pixel_ratio') != -1) {
					window.location.reload();
				}
 			}	
		
			$('html').removeClass('no-js');
			
			// Detect mobile browser
			if( (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) || (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.platform)) ) {
				$('html').addClass('mobile');
			}
			
			// Detect IE
			if (navigator.appName == "Microsoft Internet Explorer") {
    		var ie = true;
    		var ua = navigator.userAgent;
    		var re = new RegExp("MSIE ([0-9]{1,}[.0-9]{0,})");
    		if (re.exec(ua) != null) {
    	    var ieVersion = parseInt(RegExp.$1);
        	$('html').addClass('ie' + ieVersion );
    		}
			} 
		},
		/**
			Equal Height
		**/
		initEqualHeight: function() {
			$('.wproto-equal').matchHeight({
				target: $('.matchHeight'),
				byRow: true,
				remove: true
			});
		},
		/**
			Go Top script
		**/
		initGoTop: function() {
			if( $('body').hasClass('no-gotop') == false && $().UItoTop ) {
				$().UItoTop();
			}
		},
		/**
			ToolTips
		**/
		initToolTips: function() {
			$('#header').find('[title]').each( function() {
				var g = $(this).data('gravity');
				if( ! g ) {
					g = 's';
				}
				$(this).tipsy({gravity: g, fade: true});
			});
			$('.ninja-forms-help-text').each( function() {
				var g = 's';
				var t = $(this).attr('title');
				$(this).tipsy({gravity: g, fade: true, title: function() { return t }});
			});
		},
		/**
			SVG Images
		**/
		initSVG: function() {
	    /*
	     * Replace all SVG images with inline SVG
	     */
        $('img.svg-img').each(function(){
            var $img = $(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');
            
            var imgWidth = $img.attr('width');
            var imgHeight = $img.attr('height');

            $.get(imgURL, function(data) {
              // Get the SVG tag, ignore the rest
              var $svg = $(data).find('svg');
              // Add replaced image's ID to the new SVG
              if(typeof imgID !== 'undefined') {
                  $svg = $svg.attr('id', imgID);
              }
              // Add replaced image's classes to the new SVG
              if(typeof imgClass !== 'undefined') {
                  $svg = $svg.attr('class', imgClass+' replaced-svg');
              }
              // Remove any invalid XML tags as per http://validator.w3.org
              $svg = $svg.removeAttr('xmlns:a');
              $svg = $svg.attr('width', imgWidth);
              $svg = $svg.attr('height', imgHeight);
              // Replace image with new SVG
              $img.replaceWith($svg);

          }, 'xml');

        });
		},
		/**
			Theme menus
		**/
		initMenus: function() {
			var $headerMenu = $('#header-menu');
			if( $headerMenu.length ) {
				
				$headerMenu.find('li.item-mega-menu').each( function() {
					$(this).parents('ul').addClass('mega-menu');
					$(this).parents('li.level-0').addClass('mega-menu-first-level-item');
				});
				
				$headerMenu.smartmenus({
					showTimeout: 0,
					subMenusSubOffsetY: 0,
					subIndicatorsPos: 'append',
					subIndicatorsText: '',
					subMenusMaxWidth: 'none',
					keepInViewport: false,
					showFunction: function($ul, complete) { $ul.fadeIn(250, complete); }
				});
				
			}
			
			$('li.menu-item').hover( function(){
				$(this).prev().addClass('prev-hover');
			}, function() {
				$(this).prev().removeClass('prev-hover');
			});
			
		},
		/**
			Init custom styles for forms
		**/
		initForms: function() {
			
			if( $('body').hasClass('no-js-form-styling') == false ) {
				
				// Custom dropdowns
				$('select:not([multiple])').not('.single-product .variations select').selectbox({
					effect: 'slide'
				});

				// Custom checkboxes and radios
				$('input[type=radio], input[type=checkbox]').not('#shipping_method input[type=radio]').ionCheckRadio();
				
			}
			
		},
		/**
			Responsive videos
		**/
		responsiveVideos: function() {
			$('.wproto-primary-content-area').fitVids();
		},
		/**
			WP Tweaks
		**/
		wpTweaks: function() {
			$('.widget_categories li').each( function() {
				var brackets = $(this).find('span').text().replace('(', '').replace(')', '');
				$(this).find('span').html( brackets );
			});
		},
		/**
			Init animations
		**/
		initAnimations: function() {
			
			var mobileAnimation = ! $('body').hasClass('disable-animation-on-mobiles');
			
		  var wow = new WOW({
				boxClass:     'wow',
				animateClass: 'animated',
				offset:       0,
				mobile:       mobileAnimation,
				live:         true,
				callback:     function(box) {
					
					var $box = $(box);
					
					if( $box.hasClass('animationProgressBar') ) {
						var w = $box.data('width');
						$box.width( w );
					}
					
					if( $box.hasClass('animationNuminate') ) {
						$box.each( function() {
							var $item = $(this);
							var to = $item.data('to');
							
							$item.numinate({ format: '%counter%', from: 1, to: to, runningInterval: 2000, stepUnit: 5});
						});
					}
					
					if( $box.hasClass('animationCircleProgress') ) {
						
						$box.each( function() {
							var $item = $(this);
							var val = $item.data('value');
							var num = $item.data('num');
							var unit = $item.data('unit');
							var fillColor = $item.data('color');
							var gradColor = $item.data('grad-color');
							var gradColorAlt = $item.data('grad-color-alt');
							
							if( $item.parent().hasClass('style-style_1') || $item.parent().hasClass('style-style_3') ) {
								var fillParams = { color: fillColor.toString() };
								var eFill = 'rgba(0, 0, 0, 0)';
							} else {
								var fillParams = { gradient: [ gradColor.toString(), gradColorAlt.toString() ] };
								var eFill = 'rgba(0, 0, 0, 0.1)';
							}
							
							$box.circleProgress({
								value: val,
								size: 130,
								thickness: 5,
								emptyFill: eFill,
								fill: fillParams
							}).on('circle-animation-progress', function( event, progress, stepValue ) {
								$(this).find('strong').html( String( stepValue.toFixed(2)).substr(2) + '<i>' + unit + '</i>' );
							});
						});
						
					}
					
				}
			});
			
		  wow.init();

		},
		/**
			Validate comments form
		**/
		validateCommentsForm: function() {
			
			var self = this;
			
			if( $('#commentform').length ) {
		
				$('#commentform').submit( function() {
					var author = $('#author');
					var comment = $('#comment');
					var email = $('#email');
					var captcha = $( this ).find('input.wproto-captcha-input');
			
					var errorCount = 0;
			
					var form = $(this);
			
					if( author.length && $.trim( author.val() ) == '' ) {
						author.focus();	
						errorCount++;		
						return false;	
					}

					if( email.length ) {
				
						var emailVal = email.val();
				
						if( $.trim(emailVal ) == '' || !self.isValidEmailAddress( email.val() ) ) {
					
							email.focus();
							errorCount++;
							return false;	
						}
					}
			
					if( $.trim( comment.val() ) == '' ) {
						comment.focus();
						errorCount++;
						return false;	
					}
			
					if( captcha.length && $.trim( captcha.val() ) == '' ) {
						captcha.focus();
						errorCount++;
						return false;	
					} else {

						if( captcha.length ) {
					
							var captcha_id = form.find('input.wproto-captcha-input-id');
							var answ = captcha.val();
					
							$.ajax({
								url: wprotoEngineVars.ajaxurl,
								type: "POST",
								data: {
									'action' : 'wproto_check_captcha_answer',
									'answer' : answ,
									'wproto_captcha_id' : captcha_id.val()
								},
								success: function( result ) {
									if( result == 'ok' ) {
										$('#commentform').unbind( 'submit' );
										$('#commentform #submit').trigger('click');
									} else {
										self.alertMessage( wprotoEngineVars.strWrongCaptcha );
										captcha.val('').focus();
									}
								},
								error: function() {
									self.alertMessage( wprotoEngineVars.strServerResponseError );
								},
								ajaxError: function() {
									self.alertMessage( wprotoEngineVars.strAJAXError );
								}
							});
				
							return false;
						} 
				
					}

				});
		
			}
			
		},
		/**
			Init Galleries
		**/
		initGalleries: function() {			

			
		},
		/**
			Init Tabs
		**/
		initTabs: function() {
			
			$('.wproto-tabs, .wproto-native-accordion').each( function() {

				var id = $(this).attr('id');
				var type = $(this).data('type');

				$('#' + id).easyResponsiveTabs({
				  type: type, //Types: default, vertical, accordion           
				  width: '600px',
				  fit: true, 
				  closed: 'accordion',
				  tabidentify: id,
			    activetab_bg: '',
			    inactive_bg: '',
			    active_border_color: '',
			    active_content_border_color: ''
				});
				
			});
			
			$('.wpb_wrapper .wproto-native-accordion').each( function() {
				$(this).find('h2.resp-accordion:first').addClass('resp-tab-active');
				$(this).find('.resp-tab-content:first').show().addClass('resp-tab-content-active').removeClass('resp-accordion-closed');
			});
			
			/** recent posts tab widget **/
			$('.wproto_posts_widget').each( function() {
				var $widget = $(this);
				
				$widget.find('.nav-tabs a').click( function() {
					
					$widget.find('.nav-tabs a').removeClass('active');
					$(this).addClass('active');
					
					var id = $(this).attr('href');
					
					$widget.find('div.widget-tab').hide();
					$( id ).fadeIn();
					
					return false;
					
				});
				
			});
			
		},
		/**
			Init Responsive Tabs
		**/
		initResponsiveTabs: function() {
			
			var wWidth = $(window).width();
			
			$('.wproto-tabs, .wproto-switched-tabs').each( function() {
				
				var $item = $(this);
				var rb = parseInt( $item.data('break') );
				
				if( wWidth <= rb ) {
					$item.removeClass('wproto-tabs wproto-tabs-shortcode').addClass('wproto-accordion wproto-switched-tabs');
				} else {
					$item.removeClass('wproto-accordion wproto-switched-tabs').addClass('wproto-tabs wproto-tabs-shortcode');
				}
				
			});
		},
		/**
			Init lightbox
		**/
		initLightbox: function() {
			
			// init lightbox
			if( $('.lightbox, .wpb_single_image a[target=_self], .attachment > a, .single-product .wproto-primary-content-area .images a, .wp-caption > a').length ) {
				$('.lightbox, .wpb_single_image a[target=_self], .attachment > a, .single-product .wproto-primary-content-area .images a, .wp-caption > a').nivoLightbox({
					effect: 'fadeScale'
				});				
			}
			
		},
		/**
			Testimonials
		**/
		initTestimonials: function() {
			
			var $testimonials = $('.wproto-testimonials-carousel, .wproto-testimonials-widget');
			
			$testimonials.each( function() {
				
				var $carousel = $(this);
				
				var speed = $carousel.data('autoplay-speed');
				speed = parseInt( speed ) == 0 ? false : speed;
				
				$carousel.owlCarousel({
					singleItem: true,
					autoHeight: true,
					navigation: true,
					pagination: true,
					autoPlay: speed,
					stopOnHover: true
				}); 
				
			});
			
		},
		/**
			OWL Carousels
		**/
		initCarousels: function() {
			
			$('.wproto-shortcode-partners-clients .items, .wproto-staff-carousel .items, .wproto_thumbnails_carousel_widget .photoalbums-carousel').each( function() {
				var $carousel = $(this);
				
				var speed = $carousel.data('autoplay-speed');
				speed = parseInt( speed ) == 0 ? false : speed;
				var items = $carousel.data('items');
				var nav = Boolean( $carousel.data('display-nav') );
				var singleItem = $carousel.hasClass('photoalbums-carousel');
				
				$carousel.owlCarousel({
					navigation: nav,
					pagination: nav,
					autoPlay: speed,
					stopOnHover: true,
					items: items,
					singleItem: singleItem
				}); 
				
			});
			
			$('.post-content-inner .post-gallery-shortcode .g-items').each( function() {
				if( $(this).parent().hasClass('bx-viewport') == false ) {
					$(this).bxSlider({
					  adaptiveHeight: true,
					  pager: true,
					  controls: true,
					  nextText: '',
					  prevText: '',
					  mode: 'fade'
					});
				}
			});
			
			$('.wproto-posts-carousel .items').each( function() {
				var $elem = $(this);
				
				var withSidebar = $('body').hasClass('page-with-sidebar');
				var items = withSidebar ? 3 : 4;
				var itemsDeskSmall = withSidebar ? 2 : 3;

				$elem.owlCarousel({
					navigation: true,
					pagination: true,
					stopOnHover: true,
					items: items,
					itemsDesktopSmall: [1216,itemsDeskSmall],
					itemsTablet: [767,2],
					itemsMobile: [480,1],
					afterInit: function() {
						
						if( $elem.find('.post-gallery-shortcode .g-items').length ) {
							
							$elem.find('.post-gallery-shortcode .g-items').waitForImages({
								waitForAll: true,
								finished: function() {
									$elem.find('.post-gallery-shortcode .g-items').bxSlider({
									  adaptiveHeight: true,
									  pager: true,
									  controls: true,
									  nextText: '',
									  prevText: '',
									  mode: 'fade'
									});	
								}
							});
							

						}
						
					}
				}); 
			});
			
			// Portfolio carousels
			$('.single-wproto_portfolio .style-full_screen_slider .single-portfolio-slider, .single-wproto_portfolio .style-image_and_details .single-portfolio-slider, .single-wproto_portfolio .style-vertical_image .single-portfolio-slider').each( function() {
				
				if( $(this).parent().hasClass('bx-viewport') == false ) {
					$(this).bxSlider({
					  adaptiveHeight: true,
					  pager: false,
					  controls: true,
					  nextText: '',
					  prevText: '',
					  mode: 'fade'
					});	
				}
				
			});

			
			// Portolio carousel with horizontal thumbnails
			$('.single-wproto_portfolio .style-gallery_horizontal_thumbnails .single-portfolio-slider, .single-wproto_portfolio .style-gallery_full_width_thumbnails .single-portfolio-slider').each( function() {
				if( $(this).parent().hasClass('bx-viewport') == false ) {
					$(this).bxSlider({
					  adaptiveHeight: true,
					  pager: true,
					  controls: true,
					  mode: 'fade',
					  nextText: '',
					  prevText: '',
					  pagerCustom: '#single-portfolio-thumbs-horizontal',
					  onSliderLoad: function() {
							$('#single-portfolio-thumbs-horizontal').mThumbnailScroller({
		            axis: 'x',
		            setWidth: '100%'
		          }).show();
					  }
					});					
				}

			});
			
			// Portolio carousel with vertical thumbnails
			$('.single-wproto_portfolio .style-gallery_vertical_thumbnails .single-portfolio-slider').each( function() {
				if( $(this).parent().hasClass('bx-viewport') == false ) {
					$(this).bxSlider({
					  adaptiveHeight: true,
					  pager: true,
					  controls: true,
					  mode: 'fade',
					  nextText: '',
					  prevText: '',
					  pagerCustom: '#single-portfolio-thumbs-vertical',
					  onSliderLoad: function() {
					  	
					  	$('#single-portfolio-thumbs-vertical').css('max-height', $('#wproto-portfolio-primary-slider').parent().height() + 'px' );
					  	
							$('#single-portfolio-thumbs-vertical').mThumbnailScroller({
		            axis: 'y',
		            setWidth: '100%',
		            setHeight: '100%'
		          }).show();
					  }
					});
				}
			});
			
			
			// Portfolio related posts carousel style 1
			$('.wproto-portfolio-related-posts.style_1').mThumbnailScroller({
        axis: 'x',
        setWidth: '100%'
      });
      
      // Portfolio related posts carousel owl style
      $('.wproto-portfolio-related-posts.style-owl').each( function() {
      	var $carousel = $(this);
      	
				$carousel.owlCarousel({
					navigation: true,
					pagination: false,
					items: 4,
					itemsDesktopSmall: [1216, 3],
					itemsTablet: [992,2],
					itemsMobile: [767,1]
				}); 
				
      });
		},
		/**
			Posts carousels shortcode
		**/
		initPostsCarousels: function() {
			
			var self = this;
			
			$('.wproto-posts-carousel-shortcode').each( function() {
				
				var $carousel = $(this);
				var id = $carousel.data('id');
				var idSelector = '#' + $carousel.attr('id');
				var slidesPerView = 'auto';
				
				// make a clone of HTML structure
				$(idSelector).find('.swiper-slide').clone().appendTo( '#wproto-posts-carousel-temp-holder-' + id );
				
				// init Swiper
		    var swiper = new Swiper( idSelector, {
	        pagination: idSelector + ' .swiper-pagination',
	        paginationClickable: true,
	        nextButton: '#wproto-posts-carousel-shortcode-nav-' + $carousel.data('id') + ' .filter-nav-right',
	        prevButton: '#wproto-posts-carousel-shortcode-nav-' + $carousel.data('id') + ' .filter-nav-left',
	        freeMode: true,
	        slidesPerView: slidesPerView,
	        spaceBetween: 0,
	        effect: 'slide'
		    });   
		    
		    // make carousel filters
		    $('#wproto-posts-carousel-shortcode-nav-' + id + ' .filter-link').off('click').on('click', function() {
		    	
		    	$('#wproto-posts-carousel-shortcode-nav-' + id + ' .filter-link').removeClass('current');
		    	$(this).addClass('current');
		    	
		    	var category = $(this).data('filter');
		    	
		    	swiper.removeAllSlides();
		    	
		    	$( '#wproto-posts-carousel-temp-holder-' + id + ' ' + category ).each( function() {
		    		swiper.appendSlide( $(this).get(0).outerHTML );
		    	});
		    	
		    	self.initLightbox();
		    	
		    	return false;
		    });
		    
		    $('#wproto-posts-carousel-block-title-' + id + ' .filter-nav-left').off('click').on('click', function() {
		    	swiper.slidePrev();
		    	return false;
	    	});
	    	
		    $('#wproto-posts-carousel-block-title-' + id + ' .filter-nav-right').off('click').on('click', function() {
		    	swiper.slideNext();
		    	return false;
	    	});
				
			});
			
		},
		/**
			Products carousels
		**/	
		initProductsCarousels: function() {
			
			$('.wproto-products-carousel .items').each( function() {
				
				var $carousel = $(this);
				
				var displayNavArrows = $carousel.data('display-nav-arrows');
				var displayNavBullets = $carousel.data('display-nav-bullets');
				var itemsDesktop = parseInt( $carousel.data('v-desktop') );
				var itemsSmallDesktop = parseInt( $carousel.data('v-small-desktop') );
				var itemsPhoneLand = parseInt( $carousel.data('v-phone-land') );
				var itemsPhone = parseInt( $carousel.data('v-phone') );
				
				$carousel.owlCarousel({
					navigation: displayNavArrows,
					pagination: displayNavBullets,
					items: itemsDesktop,
					itemsDesktopSmall: [1199, itemsSmallDesktop],
					itemsTablet: [995, itemsPhoneLand],
					itemsMobile: [600, itemsPhone],
					afterInit: function() {

						if( $carousel.find('.g-items').parent().hasClass('bx-viewport') == false ) {
							
							$carousel.waitForImages({
								waitForAll: true,
								finished: function() {
									$carousel.find('.g-items').bxSlider({
									  adaptiveHeight: true,
									  pager: false,
									  controls: true,
									  nextText: '',
									  prevText: '',
									  mode: 'fade'
									});	
								}
							});
													
						}

					}
				}); 
				
			});
			
			$('.wproto-products-grid article').each( function() {
				if( $(this).find('.g-items').parent().hasClass('bx-viewport') == false ) {
					$(this).find('.g-items').bxSlider({
					  adaptiveHeight: true,
					  pager: false,
					  controls: true,
					  nextText: '',
					  prevText: '',
					  mode: 'fade'
					});					
				}

			});
			
		},
		/**
			Latest tweets
		**/
		initLatestTweets: function() {
			/**
				Loading latest tweets via AJAX
			**/
			var $wprotoTweetsContainer = $('.wproto_latest_tweets');
			
			$wprotoTweetsContainer.each( function() {
				
				var $item = $(this);
				var count = $item.data('count');
				var style = $item.data('style');
				
				$.ajax({
					url: wprotoEngineVars.ajaxurl,
					type: "POST",
					data: {
						'action' : 'wproto_get_latest_tweets',
						'count' : count,
						'style' : style
					},
					success: function( response ) {
						
						$item.find('.loading').replaceWith( response );
						$item.find('i.fa').removeClass('show-animation');
						
						var $carousel = $item.find('.tweets-carousel.style-default');
						
						if( $carousel.length ) {
							
							$carousel.each( function() {
								
								var speed = $item.data('autoplay-speed');
								speed = parseInt( speed ) == 0 ? false : speed;
								
								$(this).owlCarousel({
									singleItem: true,
    							autoHeight: true,
    							pagination: true,
    							autoPlay: speed,
    							stopOnHover: true,
    							beforeMove: function() {
    								
    							},
    							afterMove: function() {
    								
    							}
  							}); 
								
							});
							
						}
						
					}
				});
				
			});

		},
		/**
			Login / register shortcode form
		**/
		initLoginRegister: function() {
			
			var self = this;
			
			$('.wproto-shortcode-login-signup').each( function() {
				
				var $elem = $(this);
				
				$elem.find('.go-to-forgot-password').click( function(){
					
					var $link = $(this);
					
					$elem.find('.page-register, .page-login').hide();
					$elem.find('.page-password-restore').fadeIn( 200 );
					$elem.find('.navigation a').removeClass('current');
					
				});
				
				$elem.find('.go-to-login').click( function(){
					
					var $link = $(this);
					
					$elem.find('.page-register, .page-password-restore').hide();
					$elem.find('.page-login').fadeIn( 200 );
					$elem.find('.navigation a').removeClass('current');
					$link.addClass('current');
					
				});
				
				$elem.find('.go-to-register').click( function(){
					
					var $link = $(this);
					
					$elem.find('.page-login, .page-password-restore').hide();
					$elem.find('.page-register').fadeIn( 200 );
					$elem.find('.navigation a').removeClass('current');
					$link.addClass('current');
						
				});
				
				$elem.find('form').submit( function() {
					
					var $form = $(this);
					
					var $loginInput = $form.find('input[name=login]');
					var $passwordInput = $form.find('input[name=password]');
					var $password2Input = $form.find('input[name=password2]');
					var $emailInput = $form.find('input[name=email]');
					
					var $firstNameInput = $form.find('input[name=first_name]');
					var $lastNameInput = $form.find('input[name=last_name]');
					
					var $termsInput = $form.find('input[name=terms]');
					
					var login = $loginInput.val();
					var password = $passwordInput.val();
					var password2 = $password2Input.val();
					var email = $emailInput.val();
					
					var type = $form.attr('class');
					
					if( $loginInput.length && $.trim( login ) == '' ) {
						$loginInput.focus();
						return false;
					}
					
					if( $emailInput.length && $.trim( email ) == '' ) {
						$emailInput.focus();
						return false;
					}
					
					if( $passwordInput.length && $.trim( password ) == '' ) {
						$passwordInput.focus();
						return false;
					}
					
					if( $password2Input.length && $.trim( password2 ) == '' ) {
						$password2Input.focus();
						return false;
					}
					
					if( $termsInput.length && ! $termsInput.is(':checked') ) {
						self.alertMessage( wprotoEngineVars.strTermsError );
						return false;
					}
					
					$.ajax({
						url: wprotoEngineVars.ajaxurl,
						type: "POST",
						dataType: "json",
						data: {
							'action' : 'wproto_register_login_form',
							'type' : type,
							'login' : $loginInput.length ? login : '',
							'email' : $emailInput.length ? email : '', 
							'first_name' : $firstNameInput.length ? $firstNameInput.val() : '',
							'last_name' : $lastNameInput.length ? $lastNameInput.val() : '',
							'password' : $passwordInput.length ? password : '',
							'password2' : $password2Input.length ? password2 : ''
						},
						beforeSend: function() {
							$form.find('input, textarea, button').attr('disabled', 'disabled');
							$form.fadeTo(500, '0.8');
						},
						success: function( result ) {
							
							$form.find('input, textarea, button').removeAttr('disabled');
							$form.fadeTo(500, '1');
						
							if( result.status == 'ok' ) {
									
								self.alertMessage( result.result_text, wprotoEngineVars.strSuccess );
								
								setTimeout(function(){
									$('.sweet-overlay, .sweet-alert').remove();
									window.location.reload(1);
								}, 5000);
									
							} else {
								
								self.alertMessage( result.error_text );

							}
							
						},
						error: function() {
								
							$form.find('input, textarea, button').removeAttr('disabled');
							$form.fadeTo(500, '1');
								
							self.alertMessage( wprotoEngineVars.strServerResponseError );
								
						},
						ajaxError: function() {
								
							$form.find('input, textarea').removeAttr('disabled');
							$form.fadeTo(500, '1');
								
							self.alertMessage( wprotoEngineVars.strAJAXError );
								
						}
					});
					
					return false;
					
				});
				
			});
			
		},
		/**
			WooCommerce events
		**/	
		initWooEvents: function() {
			
			$('.widget_product_categories').each( function() {
				
				var $elem = $(this);
				
				if( $elem.find('ul.product-categories > li > ul').length ) {
					$elem.find('ul.product-categories').addClass('wproto-hierarchical');
				}
				
				$elem.find('ul.wproto-hierarchical ul').hide();
				
				$elem.find('ul.wproto-hierarchical > li').each( function() {
					if( $(this).find('ul.children').length ) {
						$(this).append('<a href="javascript:;" class="wproto-expand-collapse"></a>');
					}
				});
				
				$elem.find('ul.wproto-hierarchical .wproto-expand-collapse').first().addClass('opened');
				$elem.find('ul.wproto-hierarchical ul.children').first().show();
				
			});
			
			$('.wproto-expand-collapse').click( function() {
				$(this).prev('ul.children').toggle('fast');
				$(this).toggleClass('opened');
			});
			
		},
		/**
			Masonry
		**/
		initMasonry: function() {
			
			var self = this;
			
			$('.masonry-posts').each( function() {
				var $elem = $(this);
				
				if( $elem.data('isotope') ) {
					$elem.isotope('destroy');
				}
				
				var $filters = $elem.prev().find('.filters-list a.filter-link');
				var $sorters = $elem.prev().find('.sort-filters select');
				var $viewSelecter = $elem.prev().find('.view-switcher select');
				
				var $m = $elem.isotope({
					layoutMode: 'masonry',
					itemSelector: '.item',
				  getSortData: {
				    date: function( elem ) {
				      return $(elem).data('date');
				    },
				    title: function( elem ) {
				      return $(elem).data('title');
				    }
				  }
				});
				
				$filters.off('click').on('click', function() {
					$filters.removeClass('current');
					$(this).addClass('current');
					var cat = $(this).data('filter');
					$m.isotope({ filter: cat });
					return false;
				});
				
				$sorters.off('change').on( 'change', function() {
					var sort = $(this).val();
					$m.isotope({ sortBy : sort });
					return false;
				});
				
				$viewSelecter.off('change').on( 'change', function() {
					
					var col = $(this).val();
					
					var $gridContainer = $( '#' + $(this).data('grid-id') );
					
					var classes = $gridContainer.attr('class');
					
					classes = classes.replace(/cols_[1-9]+/, 'cols_' + col );
					
					$gridContainer.attr( 'class', classes );
					
					self.initMasonry();
					
					return false;
				});
				
			});
			
		},
		/**
			Hover directions
		**/
		initHoverDir: function() {
			$('.portfolio-style-full_width_alt, .portfolio-style-full_width_third').find('.post-thumbnail').each( function() { $(this).hoverdir(); } );
		},
		/**
			Make alert
		**/
		alertMessage: function( text, title ) {
			
			title = (typeof title === 'undefined') ? wprotoEngineVars.strError : title;
			
			swal({
				title: title,
				text: text,
				type: "error",
				confirmButtonText: "OK",
				confirmButtonColor: ''
			});
			
		},
		/**
			Check email address
		**/
		isValidEmailAddress: function( emailAddress ) {
			var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
 			return pattern.test( emailAddress );
		},
		/**
			String to boolean
		**/
		stringToBoolean: function(string){
			
			switch(string){
				case "true": case "yes": case "1": return true;
				case "false": case "no": case "0": case null: return false;
				default: return Boolean(string);
			}
		},
		/**
			Refresh wishlist
		**/
		refreshWishlistNum: function() {
			$.ajax({
				url: wprotoEngineVars.ajaxurl,
				type: "POST",
				dataType: 'json',
				data: {
					'action' : 'wproto_refresh_wishlist_num'
				},
				success: function( response ) {
					$('#wproto-top-wishlist-num').html( response.num );
					$('#wproto-top-bar-wishlist-widget').html( response.wishlist );
				}
			});
		}
	}

	window.wprotoFrontCore.initialize();

})( window.jQuery );