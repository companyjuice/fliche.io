/**
 * Video gallery Google Adsense javascript file 
 * Enable the Google Adsense in videoplayer
 * 
 * @category   VidFlix
 * @package    Fliche Video Gallery
 * @version    0.9.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
var pagearray=new Array();
var timerout1 ;
var timerout;
var timerout2;
var timerout3;
pageno =0 ;
setTimeout('onplayerloaded()',100);
pagearray[0]= pagepath;


function getFlashMovie(movieName)
{
    var isIE = navigator.appName.indexOf("Microsoft") != -1;
    return (isIE) ? window[movieName] : document[movieName];
}

function googleclose()
{
    if(document.all)
    {
        document.all.IFrameName.src="";
    }
    else
    {
        document.getElementById("IFrameName").src="";
    }
    document.getElementById('lightm').style.display="none";
    clearTimeout();
}

function onplayerloaded()
{
   pageno=1;
   timerout1 =window.setTimeout('bindpage(0)', 1000);
}

function findPosX(obj)
{
    var curleft = 0;
    if(obj.offsetParent)
        while(1)
        {
            curleft += obj.offsetLeft;
            if(!obj.offsetParent)
                break;
            obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
    return curleft;
}

function findPosY(obj)
{
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
            curtop += obj.offsetTop;
            if(!obj.offsetParent)
                break;
            obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
    return curtop;
}

function closediv()
{

 document.getElementById('lightm').style.display="none";
 clearTimeout();
 if( ropen!='' || ropen != 0){setTimeout('bindpage(0)', ropen); }
}

function bindpage(pageno)
{
    if(document.all)
    {
        document.all.IFrameName.src=pagepath;
    }
    else
    {
        document.getElementById("IFrameName").src=pagearray[pageno];
    }
    document.getElementById('closeimgm').style.display="block";
    document.getElementById('lightm').style.display="block";
    if(closeadd !='' || closeadd !=0) setTimeout('closediv()', closeadd);
}