<?php

/**
 * The autoloader.
 * 
 * @category Autoloading
 * @package  Testes
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) Trey Shugart 2010 http://europaphp.org/
 */
class Testes_Autoloader
{
    /**
     * The NS to autoload.
     * 
     * @var string
     */
    const NS = 'Testes';

    /**
     * The registered namespaces.
     * 
     * array $namespace => $path
     * 
     * @var array
     */
    protected static $paths = array();
    
    /**
     * The framework path.
     * 
     * @var string
     */
    protected static $frameworkPath;

    /**
     * Whether or not it has been registered with SPL yet.
     * 
     * @var bool
     */
    protected static $isRegistered = false;
    
    /**
     * Registers autoloading.
     * 
     * @return void
     */
    public static function register($path = null)
    {
        // format path and check path
        if ($path) {
            $temp = realpath($path);
            if (!$temp) {
                throw new Testes_Exception('The test path "' . $path . '" is not valid.');
            }

            // register the namespace and it's associated path
            self::$paths[$temp] = $temp;
        }
        
        // set defaults
        self::registerFramework();
        self::registerAutoload();
    }
    
    /**
     * Autoloads the specified class in this NS.
     * 
     * @return void
     */
    public static function autoload($class)
    {
        // get the base file name
        $basename = str_replace(array('_', '\\'), '/', $class) . '.php';
        
        // test for framework files
        if (strpos($class, self::NS) !== false) {
            include self::$frameworkPath . '/' . $basename;
        }

        // load any of the registered files
        foreach (self::$paths as $path) {
            $path = $path . '/' . $basename;
            if (is_file($path)) {
                include $path;
                break;
            }
        }
    }

    /**
     * Registeres the framework path if it hasn't been registered yet.
     * 
     * @return void
     */
    protected static function registerFramework()
    {
        if (!self::$frameworkPath) {
            self::$frameworkPath = realpath(dirname(__FILE__) . '/../');
        }
    }

    /**
     * Registeres autoloading if it hasn't been registered yet.
     * 
     * @return void
     */
    protected static function registerAutoload()
    {
        if (!self::$isRegistered) {
            spl_autoload_register(array(self::NS . '_Autoloader', 'autoload'));
            self::$isRegistered = true;
        }
    }
}