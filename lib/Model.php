<?php

class Model
{
    protected $config = array();
    
    protected $collections = array();
    
    protected static $instances = array();
    
    protected static $defaultInstance = 'default';
    
    protected static $defaultConfig = array(
        'adapter'    => null,
        'collection' => ':collection_:adapter'
    );
    
    public function __construct(array $config = array())
    {
        $this->config = array_merge_recursive(self::$defaultConfig, $this->config, $config);
        if (!$this->config['adapter']) {
            throw new Model_Exception(
                'The adapter configuration variable must be specified.'
            );
        }
    }
    
    public function __get($name)
    {
        return $this->get($name);
    }
    
    public function __unset($name)
    {
        return $this->clear($name);
    }
    
    public function get($name)
    {
        if (isset($this->collections[$name])) {
            return $this->collections[$name];
        }
        
        // create the collection
        $class = $this->formatCollection($this->config['collection']);
        $class = new $class;
        
        // cache it
        $this->collections[$name] = $class;
        
        // and return it
        return $class;
    }
    
    public function clear($name)
    {
        if (isset($this->collections[$name])) {
            unset($this->collections[$name]);
        }
        return $this;
    }
    
    protected function formatCollection($collection)
    {
        $class = str_replace(':collection', ucfirst($name), $collection);
        $class = str_replace(':adapter', ucfirst($this->config['adapter']), $class);
        return $class;
    }
    
    public static function setInstance($name = null, Model $model)
    {
        self::$instances[$model] = $model;
    }
    
    public static function getInstance($name = null, array $config = array())
    {
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($config);
        }
        return self::$instances[$name];
    }
    
    public static function setDefaultInstance($name = 'default')
    {
        self::$defaultInstance = $name;
    }
    
    public static function setDefaultConfig(array $config = 'array')
    {
        self::$defaultConfig = $config;
    }
}