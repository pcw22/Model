<?php

/**
 * The model autoloader.
 * 
 * @category Autoloading
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model_Autoloader
{
    /**
     * The namespace of the autoloader.
     * 
     * @var string
     */
    const NS = 'Model';
    
    /**
     * Registeres autoloading.
     * 
     * @return void
     */
    public static function register()
    {
        spl_autoload_register(array(self::NS . '_Autoloader', 'autoload'));
    }
    
    /**
     * Autoloads the specified class.
     * 
     * @param string $class The class to autoload.
     * 
     * @return void
     */
    public static function autoload($class)
    {
        if (strpos($class, self::NS) === 0) {
            include dirname(__FILE__) . '/../' . str_replace(array('_', '\\'), '/', $class) . '.php';
        }
    }
}