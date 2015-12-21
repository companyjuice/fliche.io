jQuery.noConflict()( function($){
	"use strict";

	var wprotoMedia;

	var wprotoBackend = {
	
		/**
			Constructor
		**/
		initialize: function() {

			this.build();
			this.events();

		},
		/**
			Build page elements
		**/
		build: function() {

			var self = this;
			
			$('#wproto_meta_featured, #wproto_post_appearance_settings, #wproto_sidebar_settings, #wproto_redirect').find('.inside *[title]').tipsy({gravity: 's'});
			
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
 			
			$( "#wproto-attached-images #wproto-metabox-content" ).sortable({
				placeholder: "ui-state-highlight"
			});
			
			/**
				Color picker
			**/
			$('.wproto-color-picker').each( function() {
				$(this).wpColorPicker();
			});	
			
			/**
				Font picker
			**/
			$('.wproto-change-font-family').each( function() {
				$(this).change( function() {
					
					var fontSource = $(this).parents('.wproto-font-picker-holder').find('.wproto-font-source-switcer').val();
					
					if( fontSource == 'custom' ) {
						$(this).parents('.wproto-font-picker-holder').hide();
						return false;
					}
					
					var font = $(this).val();
					
					if( font == '' ) {
						
						$(this).parents('.wproto-font-picker-holder').find('.wproto-font-preview-block').hide();
						return false;
						
					} else {
						
						$(this).parents('.wproto-font-picker-holder').find('.wproto-font-preview-block').show();
						
					}
					
					$('head').append('<link href="http://fonts.googleapis.com/css?family=' + font + ':300,400,800,400italic,300italic,800italic" rel="stylesheet" type="text/css">');
					
					$(this)
					.parents('.wproto-font-picker-holder')
					.find('p.wproto-font-preview')
					.css('font-family', font )
					.css('font-weight', $(this).parents('.wproto-font-picker-holder').find('.wpl-font-weight').val() )
					.css('line-height', $(this).parents('.wproto-font-picker-holder').find('.wpl-line-height').val() + 'px' )
					.css('font-style', $(this).parents('.wproto-font-picker-holder').find('.wpl-font-style').val() );
					
				});
			});	
			
			$('.wpl-line-height, .wpl-font-weight, .wpl-font-style').on('change', function() {
				$(this).parents('.wproto-font-picker-holder').find('.wproto-change-font-family').trigger('change');
			});
			
			/**
				Change font source
			**/
			$('.wproto-font-source-switcer').each( function() {
				$(this).change( function() {
					var source = $(this).val();
					
					if( source == 'custom' ) {
						$(this).parents('.wproto-font-picker-holder').find('.wproto-font-family-google, .wproto-font-preview-block').hide();
						$(this).parents('.wproto-font-picker-holder').find('.wproto-font-family-custom').show();
						
						$(this).parents('.wproto-font-picker-holder').find('.wproto-custom-font-selecter').attr('name', $(this).data('name') );
						$(this).parents('.wproto-font-picker-holder').find('.wproto-font-family-google select').attr('name', '' );
												
					} else {
						$(this).parents('.wproto-font-picker-holder').find('.wproto-font-family-google, .wproto-font-preview-block').show();
						$(this).parents('.wproto-font-picker-holder').find('.wproto-font-family-custom').hide();
						
						$(this).parents('.wproto-font-picker-holder').find('.wproto-custom-font-selecter').attr('name', '');
						$(this).parents('.wproto-font-picker-holder').find('.wproto-font-family-google select').attr('name', $(this).data('name') );
						
					}
					
				});
			});
			
			$('.wproto-custom-font-selecter').each( function() {
				$(this).change( function() {
					var fontID = $(this).find(':selected').data('id');
					$(this).parents('.wproto-font-picker-holder').find('.wproto-hidden-custom-font-id').val( fontID );
				});
			});
			
			/**
				Slider
			**/
			$('.wproto-slider-ui').each( function() {
				
				var min = $(this).data('min');
				var max = $(this).data('max');
				var value = $(this).data('value');
				var slider = $(this);
				
				$(this).slider({
      		min: min,
      		max: max,
      		range: "min",
      		value: value,
      		slide: function( event, ui ) {
        		slider.next('input[type=text]').val( ui.value );
      		}
    		});
			});
			
			/**
				Datepicker
			**/
			$('.wproto-date-picker').each( function() {
				var dPicker = $(this);
				$(this).datepicker( { dateFormat: dPicker.data('date-format') } );
			});		
			
			
			/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				Image chooser
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
	
			$('#wproto-img-picker-add-images').click( function() {
		
				if ( wprotoMedia ) {
					wprotoMedia.open();
					return;
				}
		
				wprotoMedia = wp.media.frames.wprotoMedia = wp.media({
					className: 'media-frame wproto-media-frame',
					frame: 'select',
					multiple: true,
					title: wprotoVars.strAttachImages,
					library: {
						type: 'image'
					},
					button: {
						text: wprotoVars.strInsertAttachedImages
					}
				});
		
				wprotoMedia.on('select', function(){
					//var media_attachment = wprotoMedia.state().get('selection').first().toJSON();
					var media_attachments = wprotoMedia.state().get('selection').toJSON();
					var already_attached = [];
			
					$('input.wproto-attached-image-item-id').each( function() {
						already_attached.push( $(this).val() );
					});
			
					var loader = $('#wproto-list-attached-images-loader');
			
					if( media_attachments.length > 0 ) {
			
						$.ajax({
							url: ajaxurl,
							type: "post",
							dataType: "json",
							data: {
								'action' : 'wproto_ajax_get_html_for_attached_images',
								'images' : media_attachments,
								'already_attached' : already_attached
							},
							beforeSend: function() {
								loader.show();
							},
							success: function( response ) {
								loader.hide();
								$('#wproto_meta_attached_images #wproto-metabox-content').append( response.html );
						
								self.countAttachedImages();
						
							},
							error: function() {
								loader.hide();		
								wprotoAlertServerResponseError();
							},
							ajaxError: function() {
								loader.hide();			
								wprotoAlertAjaxError();
							}
						});
				
					}

				});
		
				wprotoMedia.open();
		
				return false;
			});
	
			$('#wproto-attached-images a.view-button').click( function() {
		
				var view = $(this).hasClass('view-table') ? 'display-table' : 'display-thumbs';
		
				$('#wproto-metabox-footer li').removeClass('current');
				$(this).parent().addClass('current');
		
				$('#wproto_meta_attached_images').removeClass('display-table display-thumbs').addClass( view );
		
				return false;
			});
		
			$( document ).on( 'click', 'a.wproto-attached-image-delete', function() {
		
				$(this).parent().fadeOut(500, function() {
					$(this).remove();
					self.countAttachedImages();
				});
		
				return false;
			});
	
			$( document ).on( 'click', 'a.wproto-attached-image-edit', function() {
		
				var id = $(this).attr('data-id');
				tb_show( '', wprotoVars.adminURL + 'media.php?attachment_id=' + id + '&action=edit&wproto_admin_noheader&TB_iframe=1');
		
				return false;
			});
			
			/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				Change post status ('Sticky' / 'Featured') by click
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
	
			$('a.wproto_change_post_status').click( function() {
		
				var loader = $('#wproto-list-posts-loader');
				var prevValue = $(this).attr('data-value');
				var postStatus = $(this).hasClass('wproto_change_sticky') ? 'sticky' : 'featured';
				var statusImage = $(this).find('img');
				var postId = $(this).attr('data-post-id');
				var link = $(this);
			
				$.ajax({
					url: ajaxurl,
					type: "post",
					dataType: "json",
					data: {
						'action' : 'wproto_ajax_change_post_status',
						'post_status' : postStatus,
						'post_id' : postId
					},
					beforeSend: function() {
						loader.show();
					},
					success: function( response ) {
						loader.hide();
				
						if( prevValue == 'true' ) {
							statusImage.attr( 'src', wprotoVars.adminIconFalse );
					
							if( postStatus == 'sticky' ) {
								$('tr#post-' + postId).find('.post-state').hide();
							}
					
							link.attr('data-value', 'false');
						} else {
							statusImage.attr( 'src', wprotoVars.adminIconTrue );
							link.attr('data-value', 'true');
					
							if( postStatus == 'sticky' ) {
						
								if( $('tr#post-' + postId + ' span.post-state').length ) {
									$('tr#post-' + postId).find('.post-state').show();
								} else {
									$('<span class="post-state"> - ' + wprotoVars.strSticky + '</span>').insertAfter( 'tr#post-' + postId + ' a.row-title' );
								}
						
							}
	
						}
				
					},
					error: function() {
						loader.hide();		
						wprotoAlertServerResponseError();
					},
					ajaxError: function() {
						loader.hide();			
						wprotoAlertAjaxError();
					}
				});
		
				return false;
			});
			
			/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				Icon picker
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
		
			/**
				Icon picker filters
			**/
			$( document ).on('change', '#icon-picker-filter', function() {
							
				var val = $(this).val();
				
				switch( val ) {
					default:
					case('all'):
						$('.font-awesome-icon-list, .icomoon-icon-list').show();
					break;
					case('font-awesome'):
						$('.font-awesome-icon-list').show();
						$('.icomoon-icon-list').hide();
					break;
					case('icomoon'):
						$('.font-awesome-icon-list').hide();
						$('.icomoon-icon-list').show();
					break;
				}
							
				return false;
			});
			
			$( document ).on('keyup', '#icon-picker-text-filter', function() {
				var val = $(this).val();
				
				$("#icon-picker-icons i").hide();
				
				$("#icon-picker-icons i:regex(class, .*" + val + ".*)").show();
				return false;
			});
		
			/**
				Choose an icon clicked
			**/
			$( document ).on('click', 'a.wproto-icon-chooser', function() {
				var iconHolder = $(this).parent().find('i.wproto-icon-holder');
				var iconInput = $(this).parent().find('input.wproto-icon-holder-input');
				var linkChooser = $(this);
				var selectedIcon = '';
				var library = 'font-awesome';
		
				$('<div id="wproto-icon-picker-dialog" title="' + wprotoVars.strIconPicker + '"></div>').appendTo('body').hide();
				var dialog = $('#wproto-icon-picker-dialog');
		
				$.ajax({
					url: ajaxurl,
					type: "post",
					dataType: "json",
					data: {
						'action' : 'wproto_ajax_show_icon_picker_form'
					},
					beforeSend: function() {
						dialog.html( wprotoVars.adminBigLoaderImage );
				
						dialog.dialog({
							modal: true,
      				height: 450,
      				width: 700,
							buttons: {
								Ok: function() {

									selectedIcon = $('#icon-picker-icons i.selected' );
									library = selectedIcon.data('library');
									var selectedIconName = selectedIcon.attr('data-name');
									iconHolder.attr( 'class', '').addClass('wproto-icon-holder fa-4x' + ' ' + selectedIconName + ' ' + library );
									
									iconInput.val( selectedIconName + ' ' + library );
									linkChooser.text( wprotoVars.strChange );
									$( this ).dialog( "close" );
								},
								Cancel: function() {
									$( this ).dialog( "close" );
								},
								'Remove Icon': function() {
									iconHolder.attr( 'class', '').addClass('wproto-icon-holder icon-2x' ).attr( 'data-name', '' );
									iconInput.val('');
									linkChooser.text( wprotoVars.strSelectIcon );
									$( this ).dialog( "close" );
								}
							}
						});
				
					},
					success: function( response ) {
						dialog.html( response.html );
						var selected = iconHolder.attr('data-name');
						if( selected != '' ) {
							$('#icon-picker-icons i.' + selected ).addClass('selected');
						}
				
					},
					error: function() {
						dialog.dialog( "close" );				
						wprotoAlertServerResponseError();
					},
					ajaxError: function() {
						dialog.dialog( "close" );				
						wprotoAlertAjaxError();
					}
				});
		
				return false;
			});
	
			/**
				Select an icon
			**/
			$( document ).on('click', '#icon-picker-icons i.wproto-icon-picker-icon', function() {
				$('#icon-picker-icons i').removeClass('selected');
				$(this).addClass('selected');
				return false;
			});
			
		},
		/**
			Set page events
		**/
		events: function() {
			
			var self = this;
			
			// Wproto Toggles editor buttons
			$('.wproto-toggles-tabs-title, wproto-toggles-tabs-content').keyup(function() {
				$(this).val($(this).val().replace(/[|]/g, ""));
			});
			
			$( document ).on( 'click', '#wproto-toggles-tabs-items .controls .add, #wproto-progress-items .controls .add', function() {
				var parent = $(this).parent().parent();
				var element = parent.clone(true);
								
				element.find('input[type=text], textarea').val('');
				element.find('input[type=number]').val(1);
				element.insertAfter( parent );
				wprotoSetupTogglesTabsItems();
				return false;
			});
	
			$( document ).on( 'click', '#wproto-toggles-tabs-items .controls .remove, #wproto-progress-items .controls .remove', function() {
				$(this).parent().parent().remove();
				wprotoSetupTogglesTabsItems();
				return false;
			});
			
			/**
				Hide installation message box
			**/
			$('#wproto-hide-demo-data-message').click( function() {
		
				var loader = $('#wproto-dismiss-demodata-loader');
		
				loader.show();
		
				$.post( ajaxurl, { 'action' : 'wproto_dismiss_demo_data_notice' },
					function( response){
						loader.hide();
						$('#wproto-first-activation-notice').fadeOut(800, function() {
							$(this).remove()
						});
					}
				);
		
				return false;
			});
			
			/**
				Hide update message box
			**/
			$('#wproto-hide-update-message').click( function() {
		
				var loader = $('#wproto-dismiss-update-loader');
		
				loader.show();
		
				$.post( ajaxurl, { 'action' : 'wproto_dismiss_update_notice' },
					function( response){
						loader.hide();
						$('#wproto-update-available-notice').fadeOut(800, function() {
							$(this).remove()
						});
					}
				);
		
				return false;
			});
			
			/**
				On / off checkboxes
			**/
			$( document ).on('click', ".cb-enable", function(){
				var parent = $(this).parents('.switch');
				$('.cb-disable',parent).removeClass('selected');
				$(this).addClass('selected');
				$('input[type=hidden]',parent).val(1).change();
			});
			$( document).on('click', ".cb-disable", function(){
				var parent = $(this).parents('.switch');
				$('.cb-enable',parent).removeClass('selected');
				$(this).addClass('selected');
				$('input[type=hidden]',parent).val(0).change();
			});
			
			/**
				Toggle elements
			**/
			$('input[data-toggle-element]').change( function() {
				var child = $( $(this).data('toggle-element') );				
				wprotoStringToBoolean( $(this).val() ) ? child.show() : child.hide();
				return false;
			});
			
			/**
				Toggle team member blocks
			**/
			$('.wproto_connect_with_wp_author').change( function() {
				if( wprotoStringToBoolean( $(this).val() ) ) {
					$('#wproto-team-author-id').show();
					$('.team-member-social-icons').hide();
				} else {
					$('#wproto-team-author-id').hide();
					$('.team-member-social-icons').show();					
				}
				return false;
			});

			/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			One Image Picker
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
	
			$( document ).on( 'click', '.wproto-image-selector', function() {
		
				var targetImage = $(this).attr('data-src-target');
				var targetInput = $(this).attr('data-url-target');
				var targetInputURL = $(this).attr('data-url-input');
		
				wprotoMedia = wp.media.frames.wprotoMedia = wp.media({
					className: 'media-frame wproto-media-frame',
					frame: 'select',
					multiple: false,
					title: wprotoVars.strSelectImage,
					library: {
						type: 'image'
					},
					button: {
						text: wprotoVars.strSelect
					}
				});
		
				wprotoMedia.on('select', function(){
					var media_attachment = wprotoMedia.state().get('selection').first().toJSON();
			
					if( targetImage != '' ) {
						$( targetImage ).attr( 'src', media_attachment.url );
					}
					if( targetInput != '' ) {
						$( targetInput ).val( media_attachment.id );
					}
					if( targetInputURL ) {
						
						var url = media_attachment.url;
						url = url.replace( 'http://' + wprotoVars.siteDomain, '' );
						url = url.replace( 'https://' + wprotoVars.siteDomain, '' );
						
						$( targetInputURL ).val( url );
					}

				});
		
				wprotoMedia.open();
		
				return false;
			});
	
			$( document ).on( 'click', '.wproto-image-remover', function(){
		
				var targetImage = $(this).attr('data-src-target');
				var targetInput = $(this).attr('data-url-target');
				var defaultImage = $(this).attr('data-default-img');
				var targetInputURL = $(this).attr('data-url-input');
		
				$( targetImage ).attr( 'src', defaultImage );
				$( targetInput ).val( '0' );
				$( targetInputURL ).val('');
		
				return false;
			});
			
			// mega menu checkbox
			$( document ).on( 'change', 'input.wproto-mega-menu-checkbox', function(){
				
				var $target = $(this).parent().next('.wproto-mega-menu-settings');
				
				$(this).is(':checked') ? $target.fadeIn() : $target.fadeOut();
				
				return false;
				
			});
			

			
		},
		
		/**************************************************************************************************************************
			Class methods
		**************************************************************************************************************************/
		
		countAttachedImages: function() {
			var items = jQuery('#wproto-metabox-content .wproto-attached-image-item').length;
	
			var holder = jQuery('#wproto-attached-images-count');
	
			if( items <= 0 ) {
				holder.html( wprotoVars.strNoImagesSelected );
			} else if( items == 1 ) {
				holder.html( wprotoVars.strOneImagesSelected );
			} else {
				holder.html( items + ' ' + wprotoVars.strImagesSelected );
			}
	
		}
		
	}
	
	wprotoBackend.initialize();
	
});