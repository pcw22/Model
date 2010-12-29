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
        'driver' => null,
        'format' => ':name_:driver'
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
        if (!$this->config['driver']) {
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
        return $this->get($name);
    }
    
    /**
     * Returns the specified driver.
     * 
     * @param string $name The driver name.
     * 
     * @return Model_Driver
     */
    public function get($name, $cached = true)
    {
        if ($cached && isset($this->drivers[$name])) {
            return $this->drivers[$name];
        }
        
        // create the driver
        $class = $this->formatDriver($name);
        $class = new $class;
        
        // check for correct instance
        if (!$class instanceof Model_Driver) {
            throw new Model_Exception(
                'The driver "'
                . $name
                . '" must be a subclass of "Model_Driver".'
            );
        }
        
        // cache it
        $this->drivers[$name] = $class;
        
        // and return it
        return $class;
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
    public static function setInstance($name = null, Model $model)
    {
        if (!$name) {
            $name = self::getDefaultInstance();
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
    public static function getInstance($name = null)
    {
        if (!$name) {
            $name = self::getDefaultInstance();
        }
        if (!self::hasInstance($name)) {
            throw new Model_Exception(
                'The model instance "'
                . $name
                . '" does not exist.'
            );
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
    public static function hasInstance($name = null)
    {
        if (!$name) {
            $name = self::getDefaultInstance();
        }
        return isset(self::$instances[$name]);
    }
    
    /**
     * Clears the specified instance if it exists.
     * 
     * @param string $name The instance name.
     * 
     * @return void
     */
    public static function clearInstance($name = null)
    {
        if (!$name) {
            $name = self::getDefaultInstance();
        }
        if (self::hasInstance($name)) {
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
    public static function setDefaultInstance($name)
    {
        self::$defaultInstance = $name;
    }
    
    /**
     * Returns the default instance name.
     * 
     * @return string
     */
    public static function getDefaultInstance()
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
        self::$defaultConfig = $config;
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