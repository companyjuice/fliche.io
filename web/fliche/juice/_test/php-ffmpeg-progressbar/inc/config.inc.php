<?php
/*
#############################################
#         PHP FFMPEG progressbar 2 v2.0     #
#    Based on FFMPEG Progressbar with PHP   #
#       (C) 2010 by Vaclav Jirovsky         #     
#     CONFIG FILE - IMPORTANT to CHANGE     # 
#############################################

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

// Security
//     Security Password
//     You don't must remember this!
//     Create someone like 'ih8chn8i9zhc gsaiodnsazd7892zhwqasiozansdf7huodtzasbasdznf' (without "&")
define("FFMPEG_PW", "PLEASE_ENTER_A_LONG_AND_SECURE_PASSWORD_FOR_SECURITY_OF_YOUR_PC");

// Path Settings
define('FFMPEG_PATH','C:\patch\to\ffmpeg.exe');
define('FFMPEG_PRESET_DIR','C\patch\to\presets\of\ffmpeg\\'); //yes, on the end two "\" !!!!
define('FFMPEG_WEB_PATH','http://localhost/progressbar/');
//     Path to dir who includes .js Files (./js/)
define('JS_PATH','./js/');

//	Language Settings
//     Country-Code (cs/en/de)
define('LANG','cs');

// Other Options
//     Update Progressbar (in milliseconds / 1000=1 second)
define('UPD_RATE', '500');

//     Load File after converting with Ajax in Div-Container (no Parameter!)
define('READY_FILE', 'form.php');

// Initializing... Don't change
require_once dirname(__FILE__).'/lang.php';

?>