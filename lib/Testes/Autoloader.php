<?php

class Testes_Autoloader
{
    const NS = 'Testes';
    
    public static function register()
    {
        spl_autoload_register(array(self::NS . '_Autoloader', 'autoload'));
    }
    
    public static function autoload($class)
    {
        if (strpos($class, self::NS) === 0) {
            include dirname(__FILE__) . '/../' . str_replace(array('_', '\\'), '/', $class) . '.php';
        }
    }
}