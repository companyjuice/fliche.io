/**
 * Video Gallery plugin script for related video scroll file.
 * 
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
function mycarousel_initCallback(carousel){
        // Disable autoscrolling if the user clicks the prev or next button.
        carousel.buttonNext.bind("click", function() {
        carousel.startAuto(0);
        });

        carousel.buttonPrev.bind("click", function() {
        carousel.startAuto(0);
        });

        // Pause autoscrolling if the user moves with the cursor over the clip.
        carousel.clip.hover(function() {
        carousel.stopAuto();
        }, function() {
        carousel.startAuto();
        });carousel.buttonPrev.bind("click", function() {
        carousel.startAuto(0);
        });
};
jQuery(document).ready(function() {
			jQuery(".jcarousel-skin-tango").jcarousel({
			auto: 0,
			wrap: "last",
			scroll:1,
			initCallback: mycarousel_initCallback
			});
});
jQuery( function (){	
 jQuery('.reportvideotype').tooltip();
});