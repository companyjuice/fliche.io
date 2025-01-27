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
    class VideoFormat_Mkv extends VideoFormat
    {
        public function __construct($input_output_type=Format::OUTPUT, Config $config=null)
        {
            parent::__construct($input_output_type, $config);
            
            // this list may be incomplete as it was documenation gleaned from 
            // http://haali.su/mkv/codecs.pdf but I didn't really understand all of it.
            $this->_restricted_audio_codecs = array(
                'ac3', 'mp1', 'mp2', 'mp3', 'dts', 'tta', 
                'libvorbis', 'vorbis', 
                'flac', 'ra_144', 
                'libfdk_aac', 'libfaac', 'aac', 'libvo_aacenc', 
                // These are audio codecs but are decode only: 'wavpack', 'ra_288', 'ralf'
                // -
                // also pcm is supported but entirely clear which codecs are supported. so some of the pcm codecs may be missing
                // or not actually supported.
                'pcm_alaw',
                'pcm_f32le',
                'pcm_f64le',
                'pcm_lxf',
                'pcm_mulaw',
                'pcm_s16le',
                'pcm_s16le_planar',
                'pcm_s24daud',
                'pcm_s24le',
                'pcm_s24le_planar',
                'pcm_s32le',
                'pcm_s32le_planar',
                'pcm_s8',
                'pcm_s8_planar',
                'pcm_u16le',
                'pcm_u24le',
                'pcm_u32le',
                'pcm_u8'
            );
            $this->_restricted_video_codecs = array('rv10', 'rv20', 'rv30', 'rv40', 'mpeg1video', 'mpeg2video', 'theora', 'snow', 'mpeg4');
            
            if($input_output_type === 'output')
            {
                $this->setAudioCodec('libvorbis')
                     ->setVideoCodec('libtheora')
                     ->setFormat('matroska');
            }
        }
    }
