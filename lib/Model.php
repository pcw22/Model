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
     * The named repositories that have been accessed.
     * 
     * @var array
     */
    protected $repositories = array();
    
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
        'repository.class' => '\Repository\:name',
        'repository.args'  => array(),
        'cache.instance'   => null
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
    }
    
    /**
     * Returns a new instance of the specified repository and passes the given
     * arguments to the constructor.
     * 
     * @param string $name The repository to get.
     * @param array  $args The arguments passed.
     * 
     * @return \Model\Repository
     */
    public function __call($name, array $args = array())
    {
        return $this->getDispatcher($name, $args);
    }
    
    /**
     * Returns the specified repository. If the repository has already been instantiated
     * the cached instance is returned.
     * 
     * @param string $name The repository to return.
     * 
     * @return \Model\Repository
     */
    public function __get($name)
    {
        if (isset($this->repositories[$name])) {
            return $this->repositories[$name];
        }
        return $this->getDispatcher($name);
    }
    
    /**
     * Returns the specified repository.
     * 
     * @param string $name The repository name.
     * 
     * @return \Model\Repository
     */
    public function getDispatcher($name, array $args = array())
    {
        // configure the dispatcher
        $dispatcher = new \Model\Dispatcher(
            $this->getRepositoryInstance($name, $args),
            $this->config['cache.instance']
        );
        
        // cache it
        $this->repositories[$name] = $dispatcher;
        
        // and return it
        return $dispatcher;
    }
    
    /**
     * Formats the repository name.
     * 
     * @param string $name The repository name.
     * 
     * @return string
     */
    private function formatRepositoryClass($name)
    {
        $class = str_replace(':name', ucfirst($name), $this->config['repository.class']);
        return $class;
    }
    
    /**
     * Returns the repository instance for the specified name.
     * 
     * @param string $name The unformatted name of the repository to return.
     * @param array  $args The arguments to pass to the repository, if any.
     * 
     * @return \Model\RepositoryInterface
     */
    private function getRepositoryInstance($name, array $args = array())
    {
        $repository = $this->formatRepositoryClass($name);
        $repository = new \ReflectionClass($repository);
        if ($repository->hasMethod('__construct')) {
            return $repository->newInstanceArgs($args ? $args : $this->config['repository.args']);
        }
        return $repository->newInstance();
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
            self::$instances[$name] = new static;
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