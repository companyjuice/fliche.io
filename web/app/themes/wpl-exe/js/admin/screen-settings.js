jQuery.noConflict()( function($){
	"use strict";

	var wprotoScreenSettings = {
	
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
			
			if( $('#wproto-custom-css-code').length ) {
				
				wprotoEnableTabOnTextarea( 'wproto-custom-css-code' );
				
			}
			
			$('#wproto-settings-screen *[title]').tipsy({gravity: 's'});
			
		},
		/**
			Set page events
		**/
		events: function() {

			var self = this;

			/**
				Compile custom LESS
			**/
			
			/** reset settings was clicked **/
			$('#wproto-customizer-form input[name=wproto_reset_to_defaults]').click( function() {
				$('#wproto-customizer-form').unbind( 'submit' );
				$('#wproto-customizer-form input[name=wproto_reset_to_defaults]').click();
				return false;
			});
			
			/** save settings was clicked **/
			$('#wproto-customizer-form').bind( 'submit', function(){
				
				var form = $(this);
				
				var customizerEnabled = $("input[name='customizer[enabled]']");
				
				if( customizerEnabled.length && wprotoStringToBoolean( customizerEnabled.val() ) ) {
					
					self.saveThemeLess( form );
					
				} else {
					form.unbind('submit').submit();
				}
				
				return false;
			});

			/**
				Skin selector
			**/
			$('.wproto-skin-selector a.skin-selector').click( function() {
				
				var skinName = $(this).data('skin');
				
				$('#wproto-skin-select-input').val( skinName );
				
				$('.wproto-skin-selector a.skin-selector img').removeClass('current');
				
				$(this).find('img').addClass('current');
				
				return false;
			});

			/**
				WPlab credits
			**/
			$('input[name="appearance[wplab_copyright]"]').change( function() {
				
				var val = $(this).val();
				var target = $(this).parent().next('p');
				wprotoStringToBoolean( val ) ? target.slideDown() : target.slideUp();
				
			});

			/**
				Image picker
			**/
			$('.wproto-background-picker .items a').click( function() {
				
				var holder = $(this).parents('.wproto-background-picker');
				
				holder.find('.items a').removeClass('selected');
				
				$(this).addClass('selected');
				
				var inputUrl = $(this).parents('.wproto-background-picker').find('input.wproto-image-picker-input');
				var inputUrl2x = $(this).parents('.wproto-background-picker').find('input.wproto-image-picker-input-2x');
				
				inputUrl.val( $(this).data('image') );
				inputUrl2x.val( $(this).data('image-2x') );
				
				return false;
			});

			/**
				Branding settings
			**/
	
			$('.wproto-header-logo-input').change( function() {
				var val = $(this).val();
				var child = $('#wproto-upload-custom-logo-header, #wproto-upload-custom-logo-header-size, #wproto-upload-custom-logo-valign');
				if( val == 'image' ) {
					child.show();
				} else if( val == '' ) {
					child.hide();
				} else {
					child.hide();
				}
		
			});
			
			$('.wproto-footer-logo-input').change( function() {
				var val = $(this).val();
				var child = $('#wproto-upload-custom-logo-footer, #wproto-upload-custom-logo-footer-size, #wproto-upload-custom-logo-footer-valign');
				if( val == 'image' ) {
					child.show();
				} else if( val == '' ) {
					child.hide();
				} else {
					child.hide();
				}
		
			});

	
			$('.wproto-remove-favicon').click( function() {
				$(this).parent().find('input[type=text]').val('').focus();
				return false;
			});

			/**
				Flush rewrite rules
			**/ 
			$( '#wproto-reset-all-settings').click( function() {

				var submitBtn = $( this );
				var resultDiv = $( '#wproto-reset-all-settings-results');
        
				resultDiv.find( 'p').html( wprotoVars.strPleaseWait );
        
				resultDiv.show();
				
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : 'wproto_reset_settings'
					},
					beforeSend: function() {
						submitBtn.attr( 'disabled', 'disabled');
					},
					success: function() {
						resultDiv.find( 'p').html( wprotoVars.strAllDone );    
						resultDiv.fadeOut(1200);
						submitBtn.attr( 'disabled', false);
					}
				});
				
			});

			/**
				Flush rewrite rules
			**/ 
			$( '#wproto-flush-rewrite-rules').click( function() {
				$( this).attr( 'disabled', true);
        
				var submitBtn = $( this );
				var resultDiv = $( '#wproto-flush-results');
        
				resultDiv.find( 'p').html( wprotoVars.strPleaseWait );
        
				resultDiv.show();

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : 'wproto_flush_rewrite_rules'
					},
					beforeSend: function() {
						submitBtn.attr( 'disabled', 'disabled');
					},
					success: function() {
						resultDiv.find( 'p').html( wprotoVars.strAllDone );    
						resultDiv.fadeOut(1200);
						submitBtn.attr( 'disabled', false);
					},
					error: function(request, status, error) {
						resultDiv.find( 'p').html( wprotoVars.strError + ": " + request.status);
					}
				});
        
				return false;
			});
			
			/**
				Reset twitter cache
			**/ 
			$( '#wproto-reset-tweets-cache').click( function() {
				$( this).attr( 'disabled', true);
        
				var submitBtn = $( this );
				var resultDiv = $( '#wproto-reset-tweets-cache-result');
        
				resultDiv.find( 'p').html( wprotoVars.strPleaseWait );
        
				resultDiv.show();

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : 'wproto_reset_tweets_cache'
					},
					beforeSend: function() {
						submitBtn.attr( 'disabled', 'disabled');
					},
					success: function() {
						resultDiv.find( 'p').html( wprotoVars.strAllDone );    
						resultDiv.fadeOut(1200);
						submitBtn.attr( 'disabled', false);
					},
					error: function(request, status, error) {
						resultDiv.find( 'p').html( wprotoVars.strError + ": " + request.status);
					}
				});
        
				return false;
			});
			
			/**
				Grab google fonts
			**/
			$('#wproto-grab-google-fonts').click( function() {
		
				var status_div = $('#wproto-google-fonts-grab-results');
				var loader = $('#wproto-grab-google-fonts-loader');
				var submitBtn = $( this );
		
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : 'wproto_grab_google_fonts_list'
					},
					beforeSend: function() {
						status_div.addClass('infodiv').removeClass('error').show();
						status_div.find('span').html( wprotoVars.strGrabbing );
						loader.show();
						submitBtn.attr( 'disabled', 'disabled');
					},
					success: function( status ) {
				
						loader.hide();
				
						if( status == 'ok' ) {
					
							loader.hide();
							status_div.addClass('infodiv');
							status_div.find('span').html( wprotoVars.strSuccess );
							status_div.fadeOut(2000);
					
						} else {
							status_div.find('span').html( wprotoVars.strCantConnectToGoogle );
							status_div.addClass('error').removeClass('infodiv');
						}
				
						submitBtn.attr( 'disabled', false);
				
					},
					error: function(request, status, error) {
						loader.hide();
						status_div.find('span').html( wprotoVars.strError + ": " + request.status );
						status_div.addClass('error').removeClass('infodiv');
					}
				});
		
				return false;
			});
			
			/**
				Demo data installer
			**/
			$('#wproto-install-demo-data').click( function() {
				
				if( $(this).attr('disabled') == 'disabled' ) {
					return false;
				}
				
				$(this).attr('disabled', 'disabled');
				
				var button = $(this);
				var resultDiv = $('#import-demo-data-results');
				
				$.ajax({
					url: ajaxurl,
					type: "POST",
					dataType: "json",
					data: {
						'action' : 'wproto_install_demo_data',
						'subaction' : 'start'
					},
					beforeSend: function() {
						resultDiv.html('<p id="install-demo-data-started">' + wprotoVars.strInstallingDemoData + '</p>').show().removeClass('error');
					},
					success: function( result ) {

						function demoInstallerStep( result ) {
							
							if( result != null && typeof( result ) == 'object' ) {
							
								if( result.answer == 'ok' ) {
								
									resultDiv.append('<p>' + result.message + '</p>');
								
									$.ajax({
										url: ajaxurl,
										type: "POST",
										dataType: "json",
										data: {
											'action' : 'wproto_install_demo_data',
											'subaction' : result.next_subaction,
											'data' : result.data
										},
										success: function( result ) {
											demoInstallerStep( result );
										},
										error: function(request, status, error) {
											resultDiv.html( '<p><strong style="color: red">' + wprotoVars.strError + ": " + request.status + '</p>' );
											button.removeAttr('disabled');
										}
									});
								
								}
							
								if( result.answer == 'finished' ) {
									$('#install-demo-data-started').remove();
									resultDiv.append('<p><strong>' + wprotoVars.strAllDone + '</strong></p>');
								}
							
							} else {
							
								resultDiv.append( '<p><strong style="color: red">' + wprotoVars.strError + ":</strong> " + wprotoVars.strWrongServerAnswer + '</p>' ).addClass('error');
								button.removeAttr('disabled');
								$('#install-demo-data-started').remove();
								
							}
							
						}

						demoInstallerStep( result );
                
					},
					error: function(request, status, error) {
						resultDiv.html( '<p><strong style="color: red">' + wprotoVars.strError + ": " + request.status + '</p>' );
						button.removeAttr('disabled');
					}
				});
				
				return false;
			});
			
			// export all theme settings
			$('#wproto-export-all-settings').click( function() {
				
				var $resultDiv = $('#wproto-export-all-settings-results');
				$resultDiv.show();
				
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : 'wproto_export_settings'
					},
					success: function( result ) {
						$('#export-settings-content').html( result );
					},
					error: function(request, status, error) {
						$resultDiv.html( '<p><strong style="color: red">' + wprotoVars.strError + ": " + request.status + '</p>' );
					}
				});
				
				return false;
			});
			
			// import theme settings
			$('#wproto-import-all-settings').click( function() {
				
				var $stringTextarea = $('#wproto-import-all-settings-textarea');
				var settingsString = $stringTextarea.val();
				var $button = $(this);
				var $resultDiv = $('#wproto-import-all-settings-results');
				
				if( $.trim( settingsString ) == '' ) {
					$stringTextarea.focus();
					return false;
				}
				
				if( $button.attr('disabled') ) {
					return false;
				}
				
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : 'wproto_import_settings',
						'settings' : settingsString
					},
					beforeSend: function() {
						$button.attr('disabled', 'disabled');
						$resultDiv.show();
					},
					success: function( result ) {
						$resultDiv.html('<p>' + result + '</p>');
					}
				});
				
				return false;
				
			});

			
		},
		/**
			Compile and save LESS
		**/
		saveThemeLess: function( form ) {
			
			var modalDialog = $( '<div title="' + wprotoVars.strPleaseWait + '">' + wprotoVars.strCompilingLess + '</div>' ).dialog({
				modal: true,
				width: 500,
				closeOnEscape: false,
				resizable: false,
				draggable: false,
				dialogClass: 'wproto-no-close'
			});
			
			var lessVars = form.serializeObject();		
			lessVars = lessVars.customizer;

			$.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					'action' : 'wproto_check_readability'
				},
				beforeSend: function() {
					modalDialog.append( wprotoVars.strCheckingReadability + '<br/>' );
				},
				success: function( checking_result ) {
					
					if( checking_result == 'error' ) {
						modalDialog.html( wprotoVars.strLessParseError + '<strong style="color: red">' + wprotoVars.strReadError + '</strong><br/>' );
						modalDialog.dialog( "option", "title", wprotoVars.strError );
						modalDialog.dialog( "option", "buttons", { "OK": function () { $( this ).dialog( "close" ); } } );
					} else {
						
						$.ajax({
							url: ajaxurl,
							type: "POST",
							data: {
								'action' : 'wproto_get_customizer_stylesheet'
							},
							beforeSend: function() {
								modalDialog.append( wprotoVars.strLoadingLessFile + '<br/>' );
							},
							success: function( file_content ) {
								modalDialog.append( wprotoVars.strLoadingLessFileSuccess + '<br/>' );
								modalDialog.append( wprotoVars.strCompilationLess + '<br/>' );
								
								var parser = less.Parser();
								
								var variable_less = "";
			    			for (var variable in lessVars ) {
			    				
			    				if( lessVars[variable] == 'yes' || lessVars[variable] == 'true' ) {
			    					variable_less += "@" + variable + ": true;";
			    				} else if ( lessVars[variable] == 'no' || lessVars[variable] == 'false' ) {
			    					variable_less += "@" + variable + ": false;";
			    				} else if( $.isNumeric( lessVars[variable] ) ) {    
			    					
			    					if( lessVars[variable] % 1 === 0){
			    						variable_less += "@" + variable + ": " + parseInt(lessVars[variable]) + ";";
			   						} else {
			   							variable_less += "@" + variable + ": " + lessVars[variable] + ";";
			   						}
			    					
			    					
			    				} else if( /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test( lessVars[variable] ) ) {
			    					variable_less += "@" + variable + ": " + lessVars[variable] + ";";
			    				} else if( lessVars[variable] == '' ) {
			    					variable_less += "@" + variable + ": wprotoSystemNoValue;";
			    				} else {
			    					variable_less += "@" + variable + ": ~'" + lessVars[variable] + "';";
			    				}
			    				
			    				if( variable == 'rounded_radius' ) {
			    					variable_less += "@rounded_radius: " + lessVars[variable] + "px;";
			    				}
			
			    			}
			    			
			    			variable_less += "@theme_mixins_path: '" + wprotoVars.themeMixinsPath + "';";
			    			variable_less += "@import ~'@{theme_mixins_path}';";
								
								console.log( variable_less );
								
								parser.parse( variable_less + ' ' + file_content, function( error, result ){
									
			    				if(error == null){
			        			try {
			        				var newCss = result.toCSS();
			        				
			        				modalDialog.append( wprotoVars.strCompilationLessSuccess + '<br/>' );
			
											$.ajax({
												url: ajaxurl,
												type: "POST",
												data: {
													'action' : 'wproto_save_customizer_stylesheet',
													'css' : newCss
												},
												beforeSend: function() {
													modalDialog.append( wprotoVars.strSavingLessIntoDB + '<br/>' );
												},
												success: function( res ) {
													
													modalDialog.html( wprotoVars.strAllDone + '<br/>' );
													modalDialog.append( wprotoVars.strRefreshing + '<br/>' );
													modalDialog.dialog( "option", "title", wprotoVars.strSuccess );
													form.unbind('submit').submit();
												}
											});
			        				
			        			} catch( e ) {
			        				console.log( e );
			    						modalDialog.html( wprotoVars.strLessParseError + '<strong style="color: red">' + e.message + '</strong><br/>' );
			    						modalDialog.dialog( "option", "title", wprotoVars.strError );
			    						modalDialog.dialog( "option", "buttons", { "OK": function () { $( this ).dialog( "close" ); } } );
			        			}
			    				} else {			    					
			  						modalDialog.html( wprotoVars.strLessParseError + '<strong style="color: red">' + error.message + '</strong><br/>' );
			  						modalDialog.dialog( "option", "title", wprotoVars.strError );
			  						modalDialog.dialog( "option", "buttons", { "OK": function () { $( this ).dialog( "close" ); } } );
			    				}
								});
								
							}
						});
						
					}
					
				}
			});

					
		}
		
	}
	
	wprotoScreenSettings.initialize();
	
});