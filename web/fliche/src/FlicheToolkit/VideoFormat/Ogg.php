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
    class VideoFormat_Ogg extends VideoFormat
    {
        public function __construct($input_output_type=Format::OUTPUT, Config $config=null)
        {
            parent::__construct($input_output_type, $config);
            
            if($input_output_type === 'output')
            {
                $this->setAudioCodec('libvorbis')
                     ->setVideoCodec('libtheora')
                     ->setFormat('ogg');
            }
            
            $this->_restricted_audio_codecs = array('libvorbis', 'vorbis');
            $this->_restricted_video_codecs = array('libtheora', 'theora');
        }
    }
