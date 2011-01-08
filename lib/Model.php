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
        'driver.class' => ':driver_:name',
        'entity.class' => ':name',
        'cache.class'  => null,
        'cache.args'   => array()
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
     * Returns a new instance of the specified driver and passes the given
     * arguments to the constructor.
     * 
     * @param string $name The driver to get.
     * @param array  $args The arguments passed.
     * 
     * @return Model_Driver
     */
    public function __call($name, array $args = array())
    {
        return $this->getDispatcher($name, $args);
    }
    
    /**
     * Returns the specified driver. If the driver has already been instantiated
     * the cached instance is returned.
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
        return $this->getDispatcher($name);
    }
    
    /**
     * Returns the specified driver.
     * 
     * @param string $name The driver name.
     * 
     * @return Model_Driver
     */
    public function getDispatcher($name, array $args = array())
    {
        // configure the dispatcher
        $dispatcher = new Model_Dispatcher(
            $this->getDriverInstance($name, $args),
            $this->formatEntityClass($name),
            $this->getCacheInstance($name)
        );
        
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
    protected function formatDriverClass($name)
    {
        $class = str_replace(':name', ucfirst($name), $this->config['driver.class']);
        $class = str_replace(':driver', ucfirst($this->config['driver']), $class);
        return $class;
    }
    
    /**
     * Formats the entity classname and returns it.
     * 
     * @param string $name The driver name.
     * 
     * @return string
     */
    protected function formatEntityClass($name)
    {
        $class = str_replace(':name', ucfirst($name), $this->config['entity.class']);
        return $class;
    }
    
    /**
     * Formats the cache classname and returns it.
     * 
     * @param string $name The driver name.
     * 
     * @return string
     */
    protected function formatCacheClass($name)
    {
        $class = str_replace(':name', ucfirst($name), $this->config['cache.class']);
        $class = str_replace(':driver', ucfirst($this->config['driver']), $class);
        return $class;
    }
    
    /**
     * Returns the driver instance for the specified name.
     * 
     * @param string $name The unformatted name of the driver to return.
     * @param array  $args The arguments to pass to the driver, if any.
     * 
     * @return Model_DriverInterface
     */
    protected function getDriverInstance($name, array $args = array())
    {
        $driver = $this->formatDriverClass($name);
        $driver = new ReflectionClass($driver);
        if ($driver->hasMethod('__construct')) {
            return $driver->newInstanceArgs($args);
        }
        return $driver->newInstance();
    }
    
    /**
     * Creates a cache instance from the configuration. If caching is disabled, then
     * it returns null.
     * 
     * @return mixed
     */
    protected function getCacheInstance($name)
    {
        if ($cache = $this->formatCacheClass($name)) {
            $cache = new ReflectionClass($cache);
            if ($cache->hasMethod('__construct')) {
                return $cache->newInstanceArgs($this->config['cache.args']);
            }
            return $cache->newInstance();
        }
        return null;
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