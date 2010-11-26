<?php

class Model
{
    protected $config = array(
        'adapter'    => null,
        'collection' => ':collection_:adapter'
    );
    
    protected $collections = array();
    
    protected static $instances = array();
    
    public function __construct(array $config = array())
    {
        $this->config = array_merge($this->config, $config);
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
        $class = $this->config['collection'];
        $class = str_replace(':collection', ucfirst($name), $class);
        $class = str_replace(':adapter', ucfirst($this->config['adapter']), $class);
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
    
    public static function getInstance(array $config = array(), $name = 'default')
    {
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($config);
        }
        return self::$instances[$name];
    }
}