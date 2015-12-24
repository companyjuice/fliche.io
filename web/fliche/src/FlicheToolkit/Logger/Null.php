<?php
	
	/**
	 * This file is part of the Fliche Video Toolkit v2 package.
	 *
	 * @author Oliver Lillie (aka buggedcom) <publicmail@buggedcom.co.uk>
	 * @license Dual licensed under MIT and GPLv2
	 * @copyright Copyright (c) 2008-2014 Oliver Lillie <http://www.buggedcom.co.uk>
	 * @package FlicheToolkit V2
	 * @version 2.1.7-beta
	 * @uses ffmpeg http://ffmpeg.sourceforge.net/
	 */
	 
	namespace FlicheToolkit;

	/**
	 * This class provides generic data parsing for the output from FFmpeg from specific
	 * media files. Parts of the code borrow heavily from Jorrit Schippers version of 
	 * FlicheToolkit v 0.1.9.
	 *
	 * @access public
	 * @author Oliver Lillie
	 * @author Jorrit Schippers
	 * @package default
	 */
	class Logger_Null implements LoggerInterface
	{
		public function log($message)
		{
			return;
		}
	}
