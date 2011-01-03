<?php

/**
 * The container for the models.
 * 
 * @category Container
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
class Model
{
    /**
     * The container configuration.
     * 
     * @var array
     */
    protected $config = array();
    
    /**
     * The named drivers that have been accessed.
     * 
     * @var array
     */
    protected $drivers = array();
    
    /**
     * The named instances that have been instantiated.
     * 
     * @var array
     */
    protected static $instances = array();
    
    /**
     * The default instance name.
     * 
     * @var string
     */
    protected static $defaultInstance = 'default';
    
    /**
     * The default configuration.
     * 
     * @var array
     */
    protected static $defaultConfig = array(
        'driver'       => null,
        'format'       => ':driver_:name',
        'cache.class'  => null,
        'cache.config' => array()
    );
    
    /**
     * Constructs a new model container.
     * 
     * @param array $config The configuration.
     * 
     * @return Model
     */
    public function __construct(array $config = array())
    {
        $this->config = array_merge_recursive(self::$defaultConfig, $this->config, $config);
        if (!isset($this->config['driver']) || !$this->config['driver']) {
            throw new Model_Exception(
                'The driver configuration variable must be specified.'
            );
        }
    }
    
    /**
     * Returns a new instance of the specified driver.
     * 
     * @param string $name The driver to get.
     * @param array  $args The arguments passed.
     * 
     * @return Model_Driver
     */
    public function __call($name, array $args = array())
    {
        return $this->get($name, false);
    }
    
    /**
     * Returns the specified driver.
     * 
     * @param string $name The driver to return.
     * 
     * @return Model_Driver
     */
    public function __get($name)
    {
        if (isset($this->drivers[$name])) {
            return $this->drivers[$name];
        }
        return $this->getDriver($name);
    }
    
    /**
     * Returns the specified driver.
     * 
     * @param string $name The driver name.
     * 
     * @return Model_Driver
     */
    public function getDriver($name)
    {
        // configure the driver
        $driver = $this->formatDriver($name);
        $cache  = $this->config['cache.class'];
        
        // configure caching
        if ($cache) {
            $cache = new $cache($this->config['cache.config']);
        }
        
        // configure the dispatcher
        $dispatcher = new Model_Dispatcher(new $driver, $cache);
        
        // cache it
        $this->drivers[$name] = $dispatcher;
        
        // and return it
        return $dispatcher;
    }
    
    /**
     * Formats the driver name.
     * 
     * @param string $name The driver name.
     * 
     * @return string
     */
    protected function formatDriver($name)
    {
        $class = str_replace(':name', ucfirst($name), $this->config['format']);
        $class = str_replace(':driver', ucfirst($this->config['driver']), $class);
        return $class;
    }
    
    /**
     * Sets the specified instance.
     * 
     * @param string $name  The instance name.
     * @param Model  $model The model instance.
     * 
     * @return void
     */
    public static function set($name = null, Model $model)
    {
        if (!$name) {
            $name = self::getDefault();
        }
        self::$instances[$name] = $model;
    }
    
    /**
     * Returns the specified instance.
     * 
     * @param string $name   The instance name.
     * @param array  $config The instance config.
     * 
     * @return Model
     */
    public static function get($name = null)
    {
        if (!$name) {
            $name = self::getDefault();
        }
        if (!self::has($name)) {
            self::$instances[$name] = new self;
        }
        return self::$instances[$name];
    }
    
    /**
     * Returns whether or not the specified instance exists.
     * 
     * @param string $name The instance name.
     * 
     * @return bool
     */
    public static function has($name = null)
    {
        if (!$name) {
            $name = self::getDefault();
        }
        return isset(self::$instances[$name]);
    }
    
    /**
     * Removes the specified instance if it exists.
     * 
     * @param string $name The instance name.
     * 
     * @return void
     */
    public static function remove($name = null)
    {
        if (!$name) {
            $name = self::getDefault();
        }
        if (self::has($name)) {
            unset(self::$instances[$name]);
        }
    }
    
    /**
     * Sets the default instance name.
     * 
     * @param string $name The instance name.
     * 
     * @return void
     */
    public static function setDefault($name)
    {
        self::$defaultInstance = $name;
    }
    
    /**
     * Returns the default instance name.
     * 
     * @return string
     */
    public static function getDefault()
    {
        return self::$defaultInstance;
    }
    
    /**
     * Sets the default config.
     * 
     * @param array $config The default config.
     * 
     * @return void
     */
    public static function setDefaultConfig(array $config)
    {
        self::$defaultConfig = array_merge(self::$defaultConfig, $config);
    }
    
    /**
     * Returns the default config.
     * 
     * @return array
     */
    public static function getDefaultConfig()
    {
        return self::$defaultConfig;
    }
}