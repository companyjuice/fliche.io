/**  
 * Video admin js file.
 *
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

/** for comma separated selected checkbox id's */
function checkingarray(checkname)
{
	checkarr   = document.getElementsByName(checkname);
	checkarray = "";
	if (checkarr.length > 0)
	{
		for (i = 0; i < checkarr.length; i++)
		{
			if (checkarr[i].checked)
			{
				checkarray += checkarr[i].value + ",";
			}
		}
		checkarray = checkarray.substring(0, checkarray.length - 1);
		return checkarray;
	}
	else
	{
		return false;
	}
}

function t1( t2 ) 
{ 
	if ( t2.value == "y" || t2 == "y" )
	{
		document.getElementById( 'upload2' ).style.display = "block";
		document.getElementById( 'supportformats' ).style.display = "";
		document.getElementById( 'ffmpeg_disable_new4' ).style.display = "";
		document.getElementById( 'ffmpeg_disable_new2' ).style.display = "";
		document.getElementById( 'ffmpeg_disable_new3' ).style.display = "";
		document.getElementById( 'ffmpeg_disable_new1' ).style.display = "";
		document.getElementById( 'ffmpeg_disable_new5' ).style.display = "";
		document.getElementById( 'ffmpeg_disable_new6' ).style.display = "";
		document.getElementById( 'filetypevalue' ).value = 2;
		document.getElementById( 'youtube' ).style.display = "none";
		document.getElementById( 'embedvideo' ).style.display = "none";
		document.getElementById( 'adstypebox' ).style.display = "block";
		document.getElementById( 'customurl' ).style.display = "none";
	} else if ( t2.value == "c" || t2 == "c" ) {
		if(document.getElementById( 'youtube' ))
			document.getElementById( 'youtube' ).style.display = "block";
		if(document.getElementById( 'upload2' ))
			document.getElementById( 'upload2' ).style.display = "block";
		if(document.getElementById( 'supportformats' ))
			document.getElementById( 'supportformats' ).style.display = "none";
		if(document.getElementById( 'supportformats' ))
		document.getElementById( 'ffmpeg_disable_new4' ).style.display = "none";
		if(document.getElementById( 'supportformats' ))
		document.getElementById( 'ffmpeg_disable_new2' ).style.display = "none";
		if(document.getElementById( 'supportformats' ))
		document.getElementById( 'ffmpeg_disable_new3' ).style.display = "none";
		if(document.getElementById( 'supportformats' ))
		document.getElementById( 'ffmpeg_disable_new1' ).style.display = "none";
		if(document.getElementById( 'supportformats' ))
		document.getElementById( 'ffmpeg_disable_new5' ).style.display = "";
		if(document.getElementById( 'supportformats' ))
		document.getElementById( 'ffmpeg_disable_new6' ).style.display = "";
		if(document.getElementById( 'supportformats' ))
		document.getElementById( 'embedvideo' ).style.display = "none";
		document.getElementById( 'customurl' ).style.display = "none";
		document.getElementById( 'adstypebox' ).style.display = "block";
		document.getElementById( 'filetypevalue' ).value = 1;
	} else if ( t2.value == "url" || t2 == "url" ) {
		document.getElementById( 'customurl' ).style.display = "block";
		document.getElementById( 'embedvideo' ).style.display = "none";
		document.getElementById( 'islive_visible' ).style.display = "none";
		document.getElementById( 'upload2' ).style.display = "block";
		document.getElementById( 'supportformats' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new4' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new2' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new3' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new1' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new5' ).style.display = "";
		document.getElementById( 'ffmpeg_disable_new6' ).style.display = "";
		document.getElementById( 'stream1' ).style.display = "none";
		document.getElementById( 'hdvideourl' ).style.display = "";
		document.getElementById( 'adstypebox' ).style.display = "block";
		document.getElementById( 'youtube' ).style.display = "none";
		document.getElementById( 'filetypevalue' ).value = 3;
	} else if ( t2.value == "rtmp" || t2 == "rtmp" ) {
		document.getElementById( 'customurl' ).style.display = "block";
		document.getElementById( 'islive_visible' ).style.display = "";
		document.getElementById( 'stream1' ).style.display = "";
		document.getElementById( 'upload2' ).style.display = "block";
		document.getElementById( 'supportformats' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new4' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new2' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new3' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new1' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new5' ).style.display = "";
		document.getElementById( 'ffmpeg_disable_new6' ).style.display = "";
		document.getElementById( 'embedvideo' ).style.display = "none";
		document.getElementById( 'hdvideourl' ).style.display = "none";
		document.getElementById( 'youtube' ).style.display = "none";
		document.getElementById( 'adstypebox' ).style.display = "block";
		document.getElementById( 'filetypevalue' ).value = 4;
	} else if ( t2.value == "embed" || t2 == "embed" ) {
		document.getElementById( 'embedvideo' ).style.display = "block";
		document.getElementById( 'islive_visible' ).style.display = "";
		document.getElementById( 'stream1' ).style.display = "";
		document.getElementById( 'customurl' ).style.display = "none";
		document.getElementById( 'hdvideourl' ).style.display = "none";
		document.getElementById( 'youtube' ).style.display = "none";
		document.getElementById( 'adstypebox' ).style.display = "none";
		document.getElementById( 'upload2' ).style.display = "block"
		document.getElementById( 'ffmpeg_disable_new3' ).style.display = ""
		document.getElementById( 'supportformats' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new4' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new2' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new1' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new5' ).style.display = "none";
		document.getElementById( 'ffmpeg_disable_new6' ).style.display = "none";
		document.getElementById( 'filetypevalue' ).value = 5;
	}
}

function VideogoogleaddeleteIds(){
	var googleadactiondown      = document.getElementById("videogoogleadactiondown").value;
	var googleadactionup        = document.getElementById("videogoogleadactionup").value;
	var googleaddeleteID        = checkingarray('videogooglead_id[]');
	if (googleadactiondown === "videogoogleaddelete" || googleadactionup === "videogoogleaddelete")
	{
		if (googleaddeleteID)
		{
			alert("You want to delete Google Adsense? ");
			return true;
		}
		else
		{
			alert("Please select a Google Adsense to delete");
			return false;
		}
	}else if(googleadactiondown ==="videogoogleadpublish" || googleadactionup ==="videogoogleadpublish" || googleadactiondown === "videogoogleadunpublish" || googleadactionup === "videogoogleadunpublish"){
		if(googleaddeleteID){
			return true;
		}else{
			alert("Please select  a googlead to  publish");
			return false;
		}
	}else {
		alert("Please select an action");
	}
	return false;
}

function PlaylistdeleteIds()
{
	playlistactiondown = document.getElementById("playlistactiondown").value;
	playlistactionup   = document.getElementById("playlistactionup").value;
	playlistID         = checkingarray('pid[]');
	if (playlistactiondown === "playlistdelete" || playlistactionup === "playlistdelete")
	{
		if (playlistID)
		{
			alert("You want to delete Category? ");
			return true;
		}
		else
		{
			alert("Please select a Category to delete");
			return false;
		}
	}else if(playlistactiondown ==="playlistpublish" || playlistactionup ==="playlistpublish" || playlistactiondown === "playlistunpublish" || playlistactionup === "playlistunpublish"){
		if(playlistID){
			return true;
		}else{
			alert("Please select  a Category to  publish");
			return false;
		}
	}else {
		alert("Please select an action");
	}
	return false;
}

function clear_upload() {
	document.getElementById("normalvideoform-value").value = '';
}

function Videoadtype(adtype)
{
	if (adtype === "prepostroll")
	{
		document.getElementById('admethod').value                  = "prepost";
		document.getElementById('videoadmethod').style.display     = "block";
		document.getElementById('videoaddetails').style.display    = "block";
		document.getElementById('adtargeturl').style.display       = "block";
		document.getElementById('addescription').style.display     = "block";
		document.getElementById('adtitle').style.display           = "block";
		document.getElementById('videoimaaddetails').style.display = "none";
		Videoadtypemethod('urlad');
	}

	if (adtype === "midroll")
	{
		document.getElementById('upload2').style.display			= "none";
		document.getElementById('videoadmethod').style.display		= "none";
		document.getElementById('admethod').value					= "midroll";
		document.getElementById('videoadurl').style.display			= "none";
		document.getElementById('videoaddetails').style.display		= "block";
		document.getElementById('adtargeturl').style.display		= "block";
		document.getElementById('addescription').style.display		= "block";
		document.getElementById('adtitle').style.display			= "block";
		document.getElementById('videoimaaddetails').style.display	= "none";
	}
	else if (adtype === "imaad")
	{
		document.getElementById('upload2').style.display			= "none";
		document.getElementById('videoadmethod').style.display		= "none";
		document.getElementById('admethod').value					= "imaad";
		document.getElementById('videoadurl').style.display			= "none";
		document.getElementById('videoaddetails').style.display		= "block";
		document.getElementById('videoimaaddetails').style.display	= "block";
		document.getElementById('adtargeturl').style.display		= "none";
		document.getElementById('addescription').style.display		= "none";
		document.getElementById('adtitle').style.display			= "";
		document.getElementById('imaadTypevideo').checked			= true;
		changeimaadtype('videoad');
	}


}
function Videoadtypemethod(adtype)
{
	if (adtype === "fileuplo")
	{
		document.getElementById('upload2').style.display	= "block";
		document.getElementById('videoadurl').style.display = "none";
		document.getElementById('adtype').style.display		= "file";
	}

	else if (adtype === "urlad")
	{
		document.getElementById('upload2').style.display	= "none";
		document.getElementById('videoadurl').style.display = "block";
		document.getElementById('adtype').value				= "url";
	}


}
function changeimaadtype(adtype)
{
	if (adtype === "textad")
	{
		document.getElementById('adimapath').style.display		= "none";
		document.getElementById('adimawidth').style.display		= "";
		document.getElementById('adimaheight').style.display	= "";
		document.getElementById('adimapublisher').style.display = "";
		document.getElementById('adimacontentid').style.display = "";
		document.getElementById('adimachannels').style.display	= "";
		document.getElementById('imaadTypetext').checked		= true;
	}

	else if (adtype === "videoad")
	{
		document.getElementById('adimapath').style.display		= "";
		document.getElementById('adimawidth').style.display		= "none";
		document.getElementById('adimaheight').style.display	= "none";
		document.getElementById('adimapublisher').style.display = "none";
		document.getElementById('adimacontentid').style.display = "none";
		document.getElementById('adimachannels').style.display	= "none";
		document.getElementById('imaadTypevideo').checked		= true;
	}
}

function validateadInput() { 
	var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
	
	if (document.getElementById('targeturl') && document.getElementById('targeturl').value !== '') {
		var thevideoadurl = document.getElementById("targeturl").value;
		if (!tomatch.test(thevideoadurl))
		{
			document.getElementById('targeterrormessage').innerHTML = 'Enter Valid Target URL';
			document.getElementById("targeturl").focus();
			return false;
		}
	} 
	if (document.getElementById('clickurl') && document.getElementById('clickurl').value !== '') {
		var thevideoadurl = document.getElementById("clickurl").value;
		if (!tomatch.test(thevideoadurl))
		{
			document.getElementById('clickerrormessage').innerHTML = 'Enter Valid Target URL';
			document.getElementById("clickurl").focus();
			return false;
		}
	} 
	if (document.getElementById('impressionurl') && document.getElementById('impressionurl').value !== '') {
		var thevideoadurl = document.getElementById("impressionurl").value;
		if (!tomatch.test(thevideoadurl))
		{
			document.getElementById('impressionerrormessage').innerHTML = 'Enter Valid Target URL';
			document.getElementById("impressionurl").focus();
			return false;
		}
	}
	if (document.getElementById('prepostroll') && document.getElementById('prepostroll').checked == true)
	{
		if (document.getElementById('filebtn') && document.getElementById('filebtn').checked === true && document.getElementById('normalvideoform-value').value === '')
		{
			document.getElementById('filepathuploaderrormessage').innerHTML = 'Upload file for Ad';
			return false;
		} 
		else if (document.getElementById('urlbtn') && document.getElementById('urlbtn').checked === true) 
		{
			if (document.getElementById('videoadfilepath').value === '') {
				document.getElementById('filepatherrormessage').innerHTML = 'Enter Ad URL';
				document.getElementById('videoadfilepath').focus();
				return false;
			} else {
				var thevideoadurl = document.getElementById("videoadfilepath").value;
				var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
				if (!tomatch.test(thevideoadurl))
				{
					document.getElementById('filepatherrormessage').style.display	= 'block';
					document.getElementById('filepatherrormessage').innerHTML		= 'Enter Valid Ad URL';
					document.getElementById("videoadfilepath").focus();
					return false;
				}
			}
		}
		var  adstitle = document.getElementById('name').value;
		     adstitle =  adstitle.trim();
		if ( adstitle == '') {
			document.getElementById('nameerrormessage').style.display	= "block";
			document.getElementById('nameerrormessage').innerHTML		= 'Enter Ad Name';
			document.getElementById('name').focus();
			return false;
		}
	}
	if (document.getElementById('imaad') && document.getElementById('imaad').checked === true) {
		if (document.getElementById('imaadTypetext').checked === true && document.getElementById('publisherId').value === '')
		{
			document.getElementById('imapublisherIderrormessage').innerHTML = 'Enter IMA Ad Publisher ID';
			document.getElementById('publisherId').focus();
			return false;

		} else if (document.getElementById('imaadTypetext').checked === true && document.getElementById('contentId').value === '')
		{
			document.getElementById('imacontentIderrormessage').innerHTML = 'Enter IMA Ad Content ID';
			document.getElementById('contentId').focus();
			return false;

		} else if (document.getElementById('imaadTypetext').checked === true && document.getElementById('channels').value === '')
		{
			document.getElementById('imachannelserrormessage').innerHTML = 'Enter IMA Ad Channel';
			document.getElementById('channels').focus();
			return false;

		} else {
			if (document.getElementById('imaadTypevideo').checked === true)
			{
				if (document.getElementById('imaadpath').value === '') {
					document.getElementById('imaadpatherrormessage').innerHTML = 'Enter IMA Ad Path';
					document.getElementById('imaadpath').focus();
					return false;
				} else {
					var thevideoadurl = document.getElementById("imaadpath").value;
					var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
					if (!tomatch.test(thevideoadurl))
					{
						document.getElementById('imaadpatherrormessage').innerHTML = 'Enter Valid IMA Ad URL';
						document.getElementById("imaadpath").focus();
						return false;
					}
				}

			}
		}

	}
	if (document.getElementById('name') && document.getElementById('name').value === '') {
		document.getElementById('nameerrormessage').style.display	= "block";
		document.getElementById('nameerrormessage').innerHTML		= 'Enter Ad Name';
		document.getElementById('name').focus();
		return false;

	}
}

function VideoaddeleteIds()
{
	videoadactiondown	= document.getElementById("videoadactiondown").value;
	videoadactionup		= document.getElementById("videoadactionup").value;
	videoadID			= checkingarray('videoad_id[]');
	if (videoadactiondown === "videoaddelete" || videoadactionup === "videoaddelete")
	{
		if (videoadID)
		{
			alert("Do you want to delete Video ad? ");
			return true;
		}
		else
		{
			alert("Please select a Video ad to delete");
			return false;
		}
	}else if(videoadactiondown ==="videoadpublish" || videoadactionup ==="videoadpublish" || videoadactiondown === "videoadunpublish" || videoadactionup === "videoadunpublish"){
		if(videoadID){
			return true;
		}else{
			alert("Please select  a Video ad to  publish");
			return false;
		}
	}
	else
	{
		alert("Please select an action");
	}
	return false;
}

function getsubtitle1name() {
	document.getElementById('subtilelang1').style.display = "";
}
function getsubtitle2name() {
	document.getElementById('subtilelang2').style.display = "";
}

function VideodeleteIds()

{
	videoactiondown = document.getElementById("videoactiondown").value;
	videoactionup	= document.getElementById("videoactionup").value;
	videoID			= checkingarray('video_id[]');
	if (videoactiondown === "videodelete" || videoactionup === "videodelete")
	{
		if (videoID)
		{
			return true;
		}
		else
		{
			alert("Please select a Video to delete ");
			return false;
		}
	}else if(videoactiondown ==="videopublish" || videoactionup ==="videopublish" || videoactiondown === "videounpublish" || videoactionup === "videounpublish"){
		if(videoID){
			return true;
		}else{
			alert("Please select  a video to  publish");
			return false;
		}
	}else if(videoactiondown ==='videofeatured' || videoactionup ==="videofeatured" || videoactiondown === "videounfeatured" || videoactionup === "videounfeatured"){
		if(videoID){
			return true;
		}else{
			alert("Please select  a video to  featured");
			return false;
		}
	}
	else
	{
		alert("Please select an action");
	}
	return false;
}

function Videotype()
{
	if (document.getElementById('uploadbtn').checked === true)
	{
		document.getElementById('videoupload').style.display	= "block";
		document.getElementById('videoyoutube').style.display	= "none";
		document.getElementById('videourl').style.display		= "none";
		document.getElementById('videoffmpeg').style.display	= "none";
	}
	if (document.getElementById('youtubebtn').checked === true)
	{
		document.getElementById('videoupload').style.display	= "none";
		document.getElementById('videoyoutube').style.display	= "block";
		document.getElementById('videourl').style.display		= "none";
		document.getElementById('videoffmpeg').style.display	= "none";
	}
	if (document.getElementById('ffmpegbtn').checked === true)
	{
		document.getElementById('videoupload').style.display	= "none";
		document.getElementById('videoyoutube').style.display	= "none";
		document.getElementById('videourl').style.display		= "none";
		document.getElementById('videoffmpeg').style.display	= "block";
	}
	if (document.getElementById('urlbtn').checked === true)
	{
		document.getElementById('videoupload').style.display	= "none";
		document.getElementById('videoyoutube').style.display	= "none";
		document.getElementById('videourl').style.display		= "block";
		document.getElementById('videoffmpeg').style.display	= "none";
	}
}

var uploadqueue		= [];
var uploadmessage	= '';

function addQueue(whichForm, myfile)
{
	var extn = extension(myfile);
	if (whichForm === 'normalvideoform' || whichForm === 'hdvideoform')
	{
	    if (extn !== 'mp3' && extn !== 'MP3' && extn !== 'flv' && extn !== 'FLV' && extn !== 'mp4' && extn !== 'MP4' && extn !== 'm4v' && extn !== 'M4V' && extn !== 'mp4v' && extn !== 'Mp4v' && extn !== 'm4a' && extn !== 'M4A' && extn !== 'mov' && extn !== 'MOV' && extn !== 'f4v' && extn !== 'F4V')		{
			alert(extn + " is not a valid Video Extension");
			return false;
		}
	} else if (whichForm === 'subtitle1form' || whichForm === 'subtitle2form')
	{
		if (extn !== 'srt' && extn !== 'SRT')
		{
			alert(extn + " is not a valid Video Extension");
			return false;
		}
	}
	else
	{
		if (extn !== 'jpg' && extn !== 'JPG' && extn !== 'jpeg' && extn !== 'JPEG' && extn !== 'png' && extn !== 'PNG' && extn !== 'gif' && extn !== 'GIF')
		{
			alert(extn + " is not a valid Image Extension");
			return false;
		}
	}
	uploadqueue.push(whichForm);
	if (uploadqueue.length === 1)
	{
		processQueue();
	}
	else
	{
		holdQueue();
	}


}
function processQueue()
{
	if (uploadqueue.length > 0)
	{
		form_handler = uploadqueue[0];
		setStatus(form_handler, 'Uploading');
		submitUploadForm(form_handler);
	}
}
function holdQueue()
{
	form_handler = uploadqueue[uploadqueue.length - 1];
	setStatus(form_handler, 'Queued');
}
function updateQueue(statuscode, statusmessage, outfile)
{ 
	uploadmessage	= statusmessage;
	form_handler	= uploadqueue[0];
	if (statuscode === 0) {
		document.getElementById(form_handler + "-value").value = outfile;
		if (form_handler === 'subtitle1form') {
			getsubtitle1name();
		}
		if (form_handler === 'subtitle2form') {
			getsubtitle2name();
		}
	}
	
	setStatus(form_handler, statuscode);
	uploadqueue.shift();
	processQueue();

}

function submitUploadForm(form_handle)
{
	document.forms[form_handle].target = "uploadvideo_target";
	document.forms[form_handle].action = "admin-ajax.php?action=uploadvideo&_wpnonce=" + upload_nonce;
	document.forms[form_handle].submit();
}
function setStatus(form_handle, status)
{
	switch (form_handle)
	{
		case "normalvideoform":
			divprefix	= 'f1';
			divmsg		= 'uploadmessage';
			divmsg1		= 'filepathuploaderrormessage';
			break;
		case "hdvideoform":
			divprefix	= 'f2';
			divmsg		= divmsg1 = '';
			break;
		case "thumbimageform":
			divprefix	= 'f3';
			divmsg		= 'uploadthumbmessage';
			divmsg1		= '';
			break;
		case "previewimageform":
			divprefix	= 'f4';
			divmsg		= divmsg1 = '';
			break;
		case "subtitle1form":
			divprefix	= 'f5';
			divmsg		= divmsg1 = '';
			break;
		case "subtitle2form":
			divprefix	= 'f6';
			divmsg		= divmsg1 = '';
			break;
	}
	switch (status)
	{
		case "Queued":
			document.getElementById(divprefix + "-upload-form").style.display		= "none";
			document.getElementById(divprefix + "-upload-progress").style.display	= "";
			document.getElementById(divprefix + "-upload-status").innerHTML			= "Queued";
			document.getElementById(divprefix + "-upload-message").style.display	= "none";
			document.getElementById(divprefix + "-upload-filename").innerHTML		= document.forms[form_handle].myfile.value;
			document.getElementById(divprefix + "-upload-image").src				= videogallery_plugin_folder + 'empty.gif';
			document.getElementById(divprefix + "-upload-cancel").innerHTML			= '<a style="padding-right:10px;" href=javascript:cancelUpload("' + form_handle + '") name="submitcancel">Cancel</a>';
			break;

		case "Uploading":
			document.getElementById(divprefix + "-upload-form").style.display		= "none";
			document.getElementById(divprefix + "-upload-progress").style.display	= "";
			document.getElementById(divprefix + "-upload-status").innerHTML			= "Uploading";
			document.getElementById(divprefix + "-upload-message").style.display	= "none";
			document.getElementById(divprefix + "-upload-filename").innerHTML		= document.forms[form_handle].myfile.value;
			document.getElementById(divprefix + "-upload-image").src				= videogallery_plugin_folder + 'loader.gif';
			document.getElementById(divprefix + "-upload-cancel").innerHTML			= '<a style="padding-right:10px;" href=javascript:cancelUpload("' + form_handle + '") name="submitcancel">Cancel</a>';
			break;
		case "Retry":
		case "Cancelled":
			document.getElementById(divprefix + "-upload-form").style.display		= "";
			document.getElementById(divprefix + "-upload-progress").style.display	= "none";
			document.forms[form_handle].myfile.value								= '';
			enableUpload(form_handle);
			break;

			
		// UPLOAD SUCCESS MESSAGE
		case 0:
			document.getElementById(divprefix + "-upload-image").src				= videogallery_plugin_folder + 'success.gif';
			document.getElementById(divprefix + "-upload-status").innerHTML			= "";
			document.getElementById(divprefix + "-upload-message").style.display	= "";
			document.getElementById(divprefix + "-upload-message").style.backgroundColor = "#CEEEB2";
			document.getElementById(divprefix + "-upload-message").innerHTML		= uploadmessage;
			if (divmsg !== '') {
				document.getElementById(divmsg).innerHTML = '';
			}
			document.getElementById(divprefix + "-upload-cancel").innerHTML			= '';
			break;


		default:
			document.getElementById(divprefix + "-upload-image").src				= videogallery_plugin_folder + 'error.gif';
			document.getElementById(divprefix + "-upload-status").innerHTML			= " ";
			document.getElementById(divprefix + "-upload-message").style.display	= "";
			document.getElementById(divprefix + "-upload-message").innerHTML		= uploadmessage + " <a href=javascript:setStatus('" + form_handle + "','Retry')>Retry</a>";
			document.getElementById(divprefix + "-upload-cancel").innerHTML			= '';
			break;
	}
}

function enableUpload(whichForm, myfile)
{
	if (document.forms[whichForm].myfile.value != '')
		document.forms[whichForm].uploadBtn.disabled = "";
	else
		document.forms[whichForm].uploadBtn.disabled = "disabled";
}

function cancelUpload(whichForm)
{
	document.getElementById('uploadvideo_target').src = '';
	setStatus(whichForm, 'Cancelled');
	pos = uploadqueue.lastIndexOf(whichForm);
	if (pos === 0)
	{
		if (uploadqueue.length >= 1)
		{
			uploadqueue.shift();
			processQueue();
		}
	}
	else
	{
		uploadqueue.splice(pos, 1);
	}
}
function chkbut()
{
	if (uploadqueue.length <= 0)
	{
		if (document.getElementById('btn2').checked)
		{
			document.getElementById('youtube-value').value = document.getElementById('filepath1').value;

			return true;
		}
		if (document.getElementById('btn3').checked || document.getElementById('btn4').checked)
		{
			document.getElementById('customurl1').value		= document.getElementById('filepath2').value;
			document.getElementById('customhd1').value		= document.getElementById('filepath3').value;
			document.getElementById('customimage').value	= document.getElementById('filepath4').value;
			document.getElementById('custompreimage').value = document.getElementById('filepath5').value;
			return true;
		}
	} else {
		alert("Wait for Uploading to Finish");
		return false;
	}

}
function extension(fname)
{
	var pos = fname.lastIndexOf(".");
	var strlen = fname.length;

	if (pos !== -1 && strlen !== pos + 1)
	{
		var ext = fname.split(".");
		var len = ext.length;
		var extension = ext[len - 1].toLowerCase();
	}
	else
	{
		extension = "No extension found";

	}
	return extension;
}

function validateInput() {

	document.getElementById('Youtubeurlmessage').innerHTML = '';
	if (document.getElementById('btn1').checked === true) {
		if (document.getElementById('filepath1').value === '') {
			document.getElementById('Youtubeurlmessage').innerHTML		= 'Enter Youtube URL';
			document.getElementById('Youtubeurlmessage').style.display	= "block";
			document.getElementById('filepath1').focus();
			return false;
		} else {
			var theurl = document.getElementById("filepath1").value;
			var regExp = /^.*(youtu.be\/|v\/|embed\/|watch\?|youtube.com\/user\/[^#]*#([^\/]*?\/)*)\??v?=?([^#\&\?]*).*/;
			var match = theurl.match(regExp);
			if (!match && theurl.indexOf("dailymotion.com") === -1 && theurl.indexOf("viddler.com") === -1) {
				document.getElementById('Youtubeurlmessage').innerHTML = 'Enter Valid Youtube URL';
				document.getElementById('filepath1').focus();
				return false;
			} else {
				document.getElementById("youtube-value").value = theurl;
			}
		}
	} else if (document.getElementById('btn2').checked === true && document.getElementById('f1-upload-form').style.display !== 'none' && document.getElementById('lbl_normal').innerHTML === '') {
		document.getElementById('uploadmessage').innerHTML = 'Upload Video';
		return false;
	} else if (document.getElementById('btn2').checked === true && document.getElementById('f3-upload-form').style.display !== 'none' && document.getElementById('thumbimageform-value').value === '') {
		document.getElementById('uploadthumbmessage').innerHTML			= 'Upload Thumb Image';
		return false;
	} else if (document.getElementById('btn3').checked === true) {
		if (document.getElementById('filepath2').value === '') {
			document.getElementById('videourlmessage').innerHTML		= 'Enter Video URL';
			document.getElementById('videourlmessage').style.display	= "block";
			document.getElementById('filepath2').focus();
			return false;
		} else {
			var thevideourl = document.getElementById("filepath2").value;
			var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
                        var allowedfileformat = /^(([a-zA-Z]:)|(\\{2}\w+)\$?)(\\(\w[\w].*))+(.mp4|.M4V|.M4A|.MOV|.mp4v|.M4V)$/;//Allowed fileformat
			if (!tomatch.test(thevideourl))
			{
				document.getElementById('videourlmessage').innerHTML		= 'Enter Valid Video URL';
				document.getElementById('videourlmessage').style.display	= "block";
				document.getElementById("filepath2").focus();
				return false;
			}
                        
		}
		var thehdvideourl = document.getElementById("filepath3").value;
		if (thehdvideourl !== '') {
			var tohdmatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
			if (!tohdmatch.test(thehdvideourl))
			{
				document.getElementById('videohdurlmessage').innerHTML	= 'Enter Valid HD Video URL';
				document.getElementById("filepath3").focus();
				return false;
			}
		}
		if (document.getElementById('filepath4').value === '') {
			document.getElementById('thumburlmessage').innerHTML		= 'Enter Thumb Image URL';
			document.getElementById('thumburlmessage').style.display = 'block';
			document.getElementById('filepath4').focus();
			return false;
		} else {
			var thethumburl		= document.getElementById("filepath4").value;
			var tothumbmatch	= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
			if (!tothumbmatch.test(thethumburl))
			{
				document.getElementById('thumburlmessage').innerHTML	= 'Enter Valid Thmub Image URL';
				document.getElementById('thumburlmessage').style.display = 'block';
				document.getElementById("filepath4").focus();
				return false;
			}
		}
		if (document.getElementById('filepath5').value !== '') {
			var thepreviewurl	= document.getElementById("filepath5").value;
			var topreviewmatch	= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
			if (!topreviewmatch.test(thepreviewurl))
			{
				document.getElementById('previewurlmessage').innerHTML	= 'Enter Valid Preview Image URL';
				document.getElementById("filepath5").focus();
				return false;
			}
		}
	} else if (document.getElementById('btn4').checked === true)
	{
		var streamer_name = document.getElementById('streamname').value;
		document.getElementById('streamerpath-value').value = streamer_name;
		var islivevalue2	= (document.getElementById('islive2').checked);
		var tomatch1		= /(rtmp:\/\/|rtmpe:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(rtmp:\/\/|rtmpe:\/\/)/;
		if (streamer_name === '') {
			document.getElementById('streamermessage').innerHTML		= 'You must provide a streamer path!';
			document.getElementById('streamermessage').style.display	= "block";
			document.getElementById('streamname').focus();
			return false;
		} else if (!tomatch1.test(streamer_name))
		{
			document.getElementById('streamermessage').innerHTML		= 'Please enter a valid streamer path';
			document.getElementById('streamermessage').style.display	= "block";
			document.getElementById('streamname').focus();
			return false;
		} else if (document.getElementById('filepath2').value === '') {
			document.getElementById('videourlmessage').innerHTML		= 'Enter Video URL';
			document.getElementById('videourlmessage').style.display	= "block";
			document.getElementById('filepath2').focus();
			return false;
		} else if (islivevalue2 === true) {
			document.getElementById('islive-value').value = 1;
		} else {
			document.getElementById('islive-value').value = 0;
		}
	}
	else if (document.getElementById('btn5') && document.getElementById('btn5').checked === true)
	{
		var embed_code	= document.getElementById('embedcode').value;
		embed_code		= (embed_code + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
		document.getElementById('embed_code').value = embed_code;
		if (embed_code === '') {
			document.getElementById('embedmessage').innerHTML = 'Enter Embed Code';
			return false;
		} else {
			document.getElementById('embedmessage').innerHTML = '';
		}
	}
	var  title = document.getElementById('name').value;
	title = title.trim();
	if (title == '') {
		document.getElementById('titlemessage').innerHTML = 'Enter Title';
		document.getElementById('titlemessage').style.display = "block";
		document.getElementById('name').focus();
		return false;
	}
	

//    validation for Video subtitle
	if (document.getElementById('subtitle1form-value').value !== "")
	{
		if (document.getElementById('subtile_lang1').value === "")
		{
			document.getElementById('uploadsrt1message').innerHTML = 'You must provide Subtitle1';
			document.getElementById('subtile_lang1').focus();
			return false;
		} else {
			document.getElementById('subtitle_lang1').value = document.getElementById('subtile_lang1').value;
		}
	}
	if (document.getElementById('subtitle2form-value').value !== "")
	{
		if (document.getElementById('subtile_lang2').value === "")
		{
			document.getElementById('uploadsrt2message').innerHTML = 'You must provide Subtitle2';
			document.getElementById('subtile_lang2').focus();
			return false;
		} else {
			document.getElementById('subtitle_lang2').value = document.getElementById('subtile_lang2').value;
		}
	}

	var check_box = document.getElementsByTagName('input');
	for (var i = 0; i < check_box.length; i++)
	{
		if (check_box[i].type == 'checkbox')
		{
			if (check_box[i].checked) {
				return true;
			}
		}
	}
	document.getElementById('jaxcat').innerHTML = 'Select any category for your Video';
	check_box[0].focus();
	return false;

}

function validateplyalistInput() {
	var playlistname = document.getElementById('playlistname').value; 
	    playlistname = playlistname.trim();
	if ( playlistname === '') {
		document.getElementById('playlistnameerrormessage').innerHTML = 'Enter Category Name';
		document.getElementById('playlistname').focus();
		return false;
	}
}

function playlistdisplay()
{
	document.getElementById('playlistcreate1').style.display = "block";
}
function playlistclose()
{
	document.getElementById('playlistcreate1').style.display = "none";
	document.getElementById('jaxcat').innerHTML = "";
	document.getElementById('message').style.display = "none";
}

function generate12(str1)
{
	var theurl = document.getElementById("filepath1").value;
	if (theurl.indexOf("youtu.be") !== -1 || theurl.indexOf("youtube.com") !== -1) {
		document.getElementById('generate').style.visibility = "visible";
		document.getElementById('Youtubeurlmessage').style.display = "none";
	} else {
		document.getElementById('generate').style.visibility = "hidden";
	}
	if (theurl.indexOf("viddler") !== -1 || theurl.indexOf("dailymotion") !== -1) {
		document.getElementById('Youtubeurlmessage').style.display = "none";
	}

}
function validatevideourl() {
	var thevideourl = document.getElementById("filepath2").value;
	if (document.getElementById('btn4').checked === true && thevideourl !== '') {
		document.getElementById('videourlmessage').style.display = "none";
	} else {
		var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
		if (tomatch.test(thevideourl))
		{
			document.getElementById('videourlmessage').style.display = "none";
		}
	}
}
function validatethumburl() {
	var thevideourl = document.getElementById("filepath4").value;
	var tomatch		= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
	if (tomatch.test(thevideourl))
	{
		document.getElementById('thumburlmessage').style.display = "none";
	}
}
function validatestreamurl() {
	var tomatch1 = /(rtmp:\/\/|rtmpe:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(rtmp:\/\/|rtmpe:\/\/)/;
	var streamer_name = document.getElementById('streamname').value;
	if (tomatch1.test(streamer_name))
	{
		document.getElementById('streamermessage').style.display = "none";
	}
}
function validatevideotitle() {
	if (document.getElementById('name').value !== '') {
		document.getElementById('titlemessage').style.display = "none";
	}
}

function validateerrormsg() {
	if (document.getElementById('videoadfilepath').value !== '') {
		document.getElementById('filepatherrormessage').style.display = "none";
	}
	if (document.getElementById('name').value !== '') {
		document.getElementById('nameerrormessage').style.display = "none";
	}
	if (document.getElementById('imaadTypetext').checked === true && document.getElementById('publisherId').value !== '')
	{
		document.getElementById('imapublisherIderrormessage').innerHTML = '';

	}
	if (document.getElementById('imaadTypetext').checked === true && document.getElementById('contentId').value !== '')
	{
		document.getElementById('imacontentIderrormessage').innerHTML = '';

	}
	if (document.getElementById('imaadTypetext').checked === true && document.getElementById('channels').value !== '')
	{
		document.getElementById('imachannelserrormessage').innerHTML = '';

	}
	if (document.getElementById('imaadTypevideo').checked === true)
	{
		if (document.getElementById('imaadpath').value !== '') {
			document.getElementById('imaadpatherrormessage').innerHTML = '';
		} else {
			var thevideoadurl = document.getElementById("imaadpath").value;
			var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
			if (tomatch.test(thevideoadurl))
			{
				document.getElementById('imaadpatherrormessage').innerHTML = '';
			}
		}

	}
}
function removeLogo()
{
	if (document.getElementById('logopathvalue').value !== ''){
		document.getElementById('logoname').innerHTML = '';
		document.getElementById('logopathvalue').innerHTML = '';
		document.getElementById('logopathvalue').value = '';
		document.getElementById('removepng').style.display = "none";
	}
}

function enablefbapi( val ) { 
    if ( val == 0 || val == 1 ) { 
        document.getElementById( 'facebook_api' ).style.display = 'table-row'; 
        document.getElementById( 'facebook_api_link' ).style.display = 'table-row'; 
        document.getElementById( 'disqus_api' ).style.display = 'none'; 
    } else if ( val == 2 ) { 
        document.getElementById( 'facebook_api' ).style.display = 'table-row'; 
        document.getElementById( 'facebook_api_link' ).style.display = 'table-row'; 
        document.getElementById( 'disqus_api' ).style.display = 'none'; 
    } else if ( val == 3 ) { 
        document.getElementById( 'facebook_api' ).style.display = 'table-row'; 
        document.getElementById( 'facebook_api_link' ).style.display = 'table-row'; 
        document.getElementById( 'disqus_api' ).style.display = 'table-row'; 
    } 
}
function enablerelateditems( val ) { 
    if ( val == 'side' ) { 
        document.getElementById( 'related_scroll_barColor' ).style.display = ''; 
        document.getElementById( 'related_scroll_barbgColor' ).style.display = ''; 
        document.getElementById( 'related_bgColor' ).style.display = ''; 
        document.getElementById( 'related_playlist_open' ).style.display = ''; 
    } else { 
        document.getElementById( 'related_scroll_barColor' ).style.display = 'none'; 
        document.getElementById( 'related_scroll_barbgColor' ).style.display = 'none'; 
        document.getElementById( 'related_bgColor' ).style.display = 'none'; 
        document.getElementById( 'related_playlist_open' ).style.display = 'none'; 
    } 
} 
var sortdr = jQuery.noConflict(); 
sortdr( function() { 
    sortdr( ".column" ).sortable( { 
        connectWith: ".column" 
    }); 
    sortdr( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" ) 
          .find( ".portlet-header" ) 
          .addClass( "ui-widget-header ui-corner-all" ) 
          .prepend( "<span class='ui-icon ui-icon-minusthick'></span>" ) 
          .end() 
          .find( ".portlet-content" );
    sortdr( ".portlet-header .ui-icon" ).click( function() { 
        sortdr( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" ); 
        sortdr( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle(); 
    } ); 
    sortdr('#videogallery_setting').click(function(){ 
        var trackcode = sortdr('#trackcode').val(); 
        var trackcodepattern = /^ua-\d{4,9}-\d{1,4}$/i; 
        if( ( !trackcodepattern.test(trackcode) )  && trackcode!='' ) { 
            sortdr('#trackcodeerror').html('Enter valid Google Analytics Tracking Code'); 
            sortdr('#trackcodeerror').addClass('updated below-h2'); 
            return false; 
        }
        return true; 
    } ); 
} ); 