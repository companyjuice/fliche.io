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
	 * @access public
	 * @author Oliver Lillie
	 * @package default
	 */
	class ImageFormat_Png extends ImageFormat
	{
		public function __construct($input_output_type=Format::OUTPUT, Config $config=null)
		{
			parent::__construct($input_output_type, $config);
			
			if($input_output_type === 'output')
			{
				$this->disableAudio()
					 ->setVideoCodec('png')
					 ->setFormat('image2');
			}
			
			$this->_restricted_audio_codecs = array();
			$this->_restricted_video_codecs = array('png');
		}
	}
