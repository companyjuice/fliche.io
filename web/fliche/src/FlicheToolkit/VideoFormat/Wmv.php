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
    class VideoFormat_Wmv extends VideoFormat
    {
        public function __construct($input_output_type=Format::OUTPUT, Config $config=null)
        {
            parent::__construct($input_output_type, $config);
            
            $this->_restricted_audio_codecs = array('wmav2', 'wmav1');
            $this->_restricted_video_codecs = array('wmv2', 'wmv1');
            
            if($input_output_type === 'output')
            {
                $this->setAudioCodec('wmav2')
                     ->setVideoCodec('wmv2')
                     ->setFormat('wmv');
            }
            else
            {
                array_push($this->_restricted_audio_codecs, 'wmalossless', 'wmapro', 'wmavoice');
                array_push($this->_restricted_video_codecs, 'wmv3', 'wmv3image');
            }
        }
    }
