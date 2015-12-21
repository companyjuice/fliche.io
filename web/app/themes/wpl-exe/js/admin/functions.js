"use strict";

/**
	Extend selectors
**/
jQuery.expr[':'].regex = function(elem, index, match) {
	var matchParams = match[3].split(','),
	validLabels = /^(data|css):/,
	attr = {
		method: matchParams[0].match(validLabels) ? 
		matchParams[0].split(':')[0] : 'attr',
		property: matchParams.shift().replace(validLabels,'')
	},
	regexFlags = 'ig',
	regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
	return regex.test(jQuery(elem)[attr.method](attr.property));
}

/**
	Set tooltips
**/
function wprotoSetTooltips( wprotoTooltip, my, at, edge, offset ) {
	jQuery( wprotoTooltip ).each( function() {
			
	var tooltipContent = '<h3>' + jQuery( this ).attr('data-pointer-title') + '</h3>' + '<p>' + jQuery( this ).attr('data-pointer-content') + '</p>';
			
	jQuery(this).hover( function() {
		jQuery( this ).pointer({
			content: tooltipContent,
			pointerClass: 'pointerTooltip',
			position: {
				my: my,
				at: at,
				edge: edge,
				offset: offset
			}
		}).pointer( 'open' );
			jQuery( '.wp-pointer-buttons a' ).hide();

		}, function() {
			jQuery(this).pointer( 'close' );
		}); 
			
	});
		
}
	
/**
	If AJAX Error spotted
**/
function wprotoAlertAjaxError() {
	jQuery( '<div title="' + wprotoVars.strError + '">' + wprotoVars.strAJAXError + '</div>' ).dialog({
		modal: true,
		buttons: {
			Ok: function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
}

/**
	If Invalid AJAX Server Response spotted
**/
function wprotoAlertServerResponseError() {
	jQuery( '<div title="' + wprotoVars.strError + '">' + wprotoVars.strServerResponseError + '</div>' ).dialog({
		modal: true,
		buttons: {
			Ok: function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
}
	
/**
	Count images at attach metabox
**/
function wprotoCountAttachedImages() {
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

/**
	Tabs / Toggles controls
**/
function wprotoSetupTogglesTabsItems() {
	jQuery('#wproto-toggles-tabs-items .item a.add, #wproto-progress-items .item a.add').hide();
	jQuery('#wproto-toggles-tabs-items .item a.remove, #wproto-toggles-tabs-items .item a.add:last, #wproto-progress-items .item a.remove, #wproto-progress-items .item a.add:last').show();
	
	if( jQuery('#wproto-toggles-tabs-items .item, #wproto-progress-items .item').length == 1 ) {
		jQuery('#wproto-toggles-tabs-items .item a.remove:first, #wproto-progress-items .item a.remove:first').hide();
	}
}

/**
	Set WP thickbox in 90% of window size
*/
function wprotoFullWidthThickbox() {

	var displayWidth = jQuery(window).width() * 0.9;
	// Animate the thickbox window to the new size (with 50px padding 
	jQuery("#TB_window").animate({
		marginLeft: 0 - (displayWidth + 50) / 2,
		width: displayWidth + 30
	}, {
		duration: 800
	});
	jQuery("#TB_iframeContent").animate({
		width: '100%'
	}, {
		duration: 800
	});
	
}

/**
	Enable TAB at textarea
**/
function wprotoEnableTabOnTextarea( id ) {
	var el = document.getElementById(id);
	el.onkeydown = function(e) {
		if (e.keyCode === 9) { // tab was pressed

			// get caret position/selection
			var val = this.value,
			start = this.selectionStart,
			end = this.selectionEnd;

			// set textarea value to: text before caret + tab + text after caret
			this.value = val.substring(0, start) + '\t' + val.substring(end);

			// put caret at right position again
			this.selectionStart = this.selectionEnd = start + 1;

			// prevent the focus lose
			return false;

		}
	};
}

/**
	String to boolean
**/
function wprotoStringToBoolean(string){
	switch(string.toLowerCase()){
		case "true": case "yes": case "1": return true;
		case "false": case "no": case "0": case null: return false;
		default: return Boolean(string);
	}
}

/**
	Generate Unique ID
**/
function wprotoGenerateUUID(){
  var d = new Date().getTime();
  var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      var r = (d + Math.random()*16)%16 | 0;
      d = Math.floor(d/16);
      return (c=='x' ? r : (r&0x3|0x8)).toString(16);
  });
  return uuid;
};