/**
 * Video Gallery plugin script file..
 * 
 * @category   FishFlicks
 * @package    Fliche Video Gallery
 * @version    0.2.9
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2015 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
function createObject() {
    var request_type;
    var browser = navigator.appName;
    if( browser == "Microsoft Internet Explorer" ) {
        request_type = new ActiveXObject( "Microsoft.XMLHTTP" );
    } else {
        request_type = new XMLHttpRequest();
    }
    return request_type;
}
function rateCalc(rating,ratecount){
	var name = videodiv + videoid; 
	var ratecal = new Function(
	     'return function ratecal' + name + '(rating,ratecount){if( rating==1 ) {document.getElementById( "rate" + name).className="ratethis onepos";}  else if( rating==2 ) {document.getElementById( "rate" + name).className="ratethis twopos";} else if( rating==3 ) {document.getElementById( "rate" + name ).className="ratethis threepos";} else if( rating==4 ) {document.getElementById( "rate" + name).className="ratethis fourpos";} else if( rating==5 ) {document.getElementById( "rate" + name ).className="ratethis fivepos";} else {document.getElementById( "rate" + name).className="ratethis nopos";}document.getElementById( "ratemsg" + name).innerHTML="Ratings&nbsp;:&nbsp;"+ratecount;}'
	     )();
	ratecal(rating,ratecount);
}
function generateRating( value ) {
	var name = videodiv + videoid;
    document.getElementById( "rate" + name).className="ratethis "+ value +"pos";
    document.getElementById( "a" + name).className="ratethis "+ value +"pos";
}
function getRating(t){
	var http = createObject();
	var name = videodiv + videoid;
	var getrate = new Function(
			'return function getrate' + name + '(t){switch (t){ case 1: generateRating( "one" ); break; case 2 : generateRating( "two" ); break; case 3: generateRating( "three" ); break; case 4: generateRating( "four" ); break; case 5: generateRating( "five" ); break; default: break;}'	
		 )();
	getrate(t);
	document.getElementById( "rate" + name).style.display="none";
    document.getElementById( "ratemsg" + name ).innerHTML="Thanks for rating!";
    var vid     = document.getElementById( "videoid" + name ).value;
    nocache     = Math.random();
    http.open( "get", baseurl+"/wp-admin/admin-ajax.php?action=ratecount&vid="+vid+"&rate="+t,true );
    http.onreadystatechange = "insertReply" + name;
    http.send( null );
    document.getElementById( "rate" + name ).style.visibility="disable";
    
    var insertReply = new Function(
			'return function insertReply' + name + '(){if( http.readyState == 4 ) { document.getElementById( "ratemsg" + name).innerHTML="Ratings&nbsp;:&nbsp;"+http.responseText; document.getElementById( "rate" + name).className=""; document.getElementById( "storeratemsg" + name ).value=http.responseText; } }'	
		 )();
    insertReply();  
}
function resetValue(ratecount){
	var name = videodiv + videoid; 
	var resetval = new Function(
	     'return function resetvalue' + name + '(ratecount){document.getElementById( "ratemsg1" + name).style.display="none"; document.getElementById( "ratemsg" + name ).style.display="block"; if( document.getElementById( "storeratemsg" + name).value == "" ) { document.getElementById( "ratemsg" + name).innerHTML="Ratings&nbsp;:&nbsp;"+ratecount; } else { document.getElementById( "ratemsg" + name).innerHTML="Ratings&nbsp;:&nbsp;"+document.getElementById( "storeratemsg" + name).value; }'
	     )();
	resetval(ratecount);
}
function displayRating(t){
	var name = videodiv + videoid; 
	var displayrate = new Function(
	     'return function displayrating' + name + '(t){if( t==1 ) { document.getElementById( "ratemsg" + name).innerHTML="Poor"; } else if( t==2 ) { document.getElementById( "ratemsg" + name).innerHTML="Nothing&nbsp;Special"; } else if( t==3 ) { document.getElementById( "ratemsg" + name).innerHTML="Worth&nbsp;Watching"; } else if( t==4 ) { document.getElementById( "ratemsg" + name).innerHTML="Pretty&nbsp;Cool"; } else if( t==5 ) { document.getElementById( "ratemsg" + name).innerHTML="Awesome"; } else { document.getElementById( "ratemsg1" + name).style.display="none"; document.getElementById( "ratemsg" + name).style.display="block"; } }'
	     )();
	displayrate(t);
}
function current_video(vid, title) {
	if (window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest;
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4)
			;
	};
	xmlhttp.open("GET", baseurl
			+ "/wp-admin/admin-ajax.php?action=videohitcount&vid=" + vid, true);
	xmlhttp.send();
}
function enableEmbed() {
	embedFlag = document.getElementById("flagembed").value;
	document.getElementById("report_video_response").style.display = "none";
	if (embedFlag != 1) {
		document.getElementById("embedcode").style.display = "block";
		document.getElementById("reportform").style.display = "none";
		document.getElementById("iframe-content").style.display = "none";
		document.getElementById("flagembed").value = "1";
		document.getElementById("reportvideo").value = "0";
		document.getElementById("iframeflag").value = "0";
	} else {
		document.getElementById("embedcode").style.display = "none";
		document.getElementById("flagembed").value = "0";
	}
}
function reportVideo() {
	var reportVideoFlag = document.getElementById("reportvideo").value;
	document.getElementById("report_video_response").style.display = "none";
	if (reportVideoFlag != 1) {
		document.getElementById("reportform").style.display = "block";
		document.getElementById("embedcode").style.display = "none";
		document.getElementById("iframe-content").style.display = "none";
		document.getElementById("reportvideo").value = "1";
		document.getElementById("flagembed").value = "0";
		document.getElementById("iframeflag").value = "0";
	} else {
		document.getElementById("reportform").style.display = "none";
		document.getElementById("reportvideo").value = "0";
	}
}
function view_iframe_code() {
	var iframeFlag = document.getElementById("iframeflag").value;
	document.getElementById("report_video_response").style.display = "none";
	if (iframeFlag != 1) {
		document.getElementById("iframe-content").style.display = "block";
		document.getElementById("reportform").style.display = "none";
		document.getElementById("embedcode").style.display = "none";
		document.getElementById("flagembed").value = "0";
		document.getElementById("reportvideo").value = "0";
		document.getElementById("iframeflag").value = "1";
	} else {
		document.getElementById("iframe-content").style.display = "none";
		document.getElementById("iframeflag").value = "0";
	}
}
function videogallery_change_player(embedcode, id, player_div, file_type, vid,
		title) {
	if (file_type === 5)
		current_video(vid, "");
	document.getElementById("mediaspace" + id).innerHTML = "";
	document.getElementById(player_div + id).innerHTML = embedcode;
	document.getElementById(player_div + id).focus();
	document.getElementById("video_title" + id).innerHTML = title;
}
function reportVideoSend() {
	var xmlhttp;
	var reporttype = document.forms["reportform"]["reportvideotype"].value;
	var reporter_email = document.forms["reportform"]["reporter_email"].value;
	var redirect_url = document.forms["reportform"]["redirect_url"].value;
	if (reporttype == "") {
		document.getElementById("report_video_response").style.display = "block";
		document.getElementById("reportform_ajax_loader").style.display = "none";
		document.getElementById("report_video_response").innerHTML = "Choose report type.";
		return false;
	}
	if (reporter_email == "") {
		document.getElementById("report_video_response").style.display = "block";
		document.getElementById("reportform_ajax_loader").style.display = "none";
		document.getElementById("report_video_response").innerHTML = "Login to Report the Video.";
		return false;
	}
	document.getElementById("reportform_ajax_loader").style.display = "block";
	var ajaxURL = baseurl
			+ "/wp-admin/admin-ajax.php?action=reportvideo&reporttype="
			+ reporttype + "&redirect_url="
			+ redirect_url;
	if (window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest;
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("reportform").style.display = "none";
			document.getElementById("reportform_ajax_loader").style.display = "none";
			document.getElementById("report_video_response").style.display = "block";
			document.getElementById("report_video_response").style.padding = "5px";
			if (xmlhttp.responseText == "fail")
				document.getElementById("report_video_response").innerHTML = "Login to Report the Video";
			else
				document.getElementById("report_video_response").innerHTML = "Thank you for submitting your report.";
		}
	};
	xmlhttp.open("GET", ajaxURL, true);
	xmlhttp.send();
}
function hideReportForm() {
	document.getElementById("reportform").style.display = "none";
	document.getElementById("reportvideo").value = "0";
	document.getElementById("reportform_ajax_loader").style.display = "none";
	document.getElementById("report_video_response").style.display = "none";
};