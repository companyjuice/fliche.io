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
    class Loggable
    {
        protected $_logger;
        
        public function setLogger(Logger $logger)
        {
            $this->_logger = $logger;
            
            return $this;
        }
        
        public function getLogger()
        {
            return $this->_logger;
        }
        
        public function log($message)
        {
            if($this->_logger)
            {
                $this->_logger->log($message);
            }
            
            return $this;
        }
    }
