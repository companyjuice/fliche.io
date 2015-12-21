jQuery.noConflict()( function($){
	"use strict";

	var wprotoTemplateEditor = {
		
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

			
		},
		/**
			Set page events
		**/
		events: function() {
			
			var self = this;
			
			/**
				Toggles
			**/
			$( document ).on( 'click', 'a.wproto-toggle-form-block', function() {
		
				$(this).parent().next('.wproto-form-table').stop().fadeToggle();
				$(this).next('i').toggleClass('icon-angle-right').toggleClass('icon-angle-down');
		
				return false;
			});
			
			/**
				Page redirect metabox
			**/
			if( $('div#wproto-redirect-form').length ) {
		
				$('.wproto_redirect_type-input').change( function() {
			
					var val = $(this).val();
			
					if( val == 'page' ) {
						$('#wproto-redirect-form-choose-page').show();
						$('#wproto-redirect-form-choose-url').hide();
					}
			
					if( val == 'url' ) {
						$('#wproto-redirect-form-choose-page').hide();
						$('#wproto-redirect-form-choose-url').show();
					}
				
					return false;
				});
		
			}
							
			/**
				Sidebar switcher
			**/
			$( document ).on( 'click', '.wproto-sidebars-layouts a', function(){
				$(this).parent().parent().find('li').removeClass('selected');
				$(this).parent().addClass('selected');
		
				var sidebar = $(this).parent().attr('data-sidebar');
				var hidden = $(this).parent().parent().parent().find('.wproto-layout-type-hide-if-no-sidebar');
		
				$('input#wproto-layout-type-input').val( sidebar );
		
				sidebar == 'none' ? hidden.hide() : hidden.show();
		
				return false;
			});
			
		}
		
	}
	
	wprotoTemplateEditor.initialize();
	
});