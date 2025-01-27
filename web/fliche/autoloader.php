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
    
    spl_autoload_register(function($class_name)
    {
        $parts = explode('\\', $class_name);
        $namespace = array_shift($parts);
        if($namespace === 'FlicheToolkit')
        {
            $class = str_replace('_', DIRECTORY_SEPARATOR, array_pop($parts));
            $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'FlicheToolkit'.DIRECTORY_SEPARATOR.ltrim(implode(DIRECTORY_SEPARATOR, $parts).DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR).$class.'.php';
            if(is_file($path) === true)
            {
                require_once $path;
            }
        }
    });
