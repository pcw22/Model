<?php

/**
 * The main driver class. All model drivers should extend this class.
 * 
 * @category Drivers
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2010 Trey Shugart http://europaphp.org/license
 */
abstract class Model_Driver
{
    /**
     * The cache driver, if any, to use for caching.
     * 
     * @var Model_Cache_DriverInterface
     */
    protected $cache = null;
    
    /**
     * The cache strategy, if any, to use for encoding the cache value.
     * 
     * @var Model_Cache_StrategyInterface
     */
    protected $strategy = null;
    
    /**
     * Saves the passed in entity.
     * 
     * @param Model_Entity $entity The model entity to save.
     * 
     * @return void
     */
    abstract public function save(Model_Entity $entity);
    
    /**
     * If an driver method is defined as anything but public, it goes through
     * __call which will automate caching if available.
     * 
     * @param string $name The driver method being called.
     * @param array  $args The arguments passed to the method.
     * 
     * @return mixed
     */
    public function __call($name, array $args = array())
    {
        // first check to see if we have a method
        if (!method_exists($this, $name)) {
            throw new Model_Exception(
                'Call to undefined driver method: '
                . get_class($this)
                . '->'
                . $name
                . '.'
            );
        }
        
        // generate a cache key for storing and retrieving
        $key = $this->generateCacheKey(get_class($this), $name, $args);
        
        // attempt to retrieve the value from the cache using the key
        if ($value = $this->_fromCache($key)) {
            return $value;
        }
        
        // otherwise, get the result from the method
        $value = call_user_func_array(array($this, $name), $args);
        
        // attempt to cache the value using the key
        $this->_toCache($key, $value);
        
        // pass on the result
        return $value;
    }
    
    /**
     * Sets the cache driver to use.
     * 
     * @param Model_Cache_DriverInterface $driver The driver to handle the cache.
     * 
     * @return Model_Driver
     */
    public function setCacheDriver(Model_Cache_DriverInterface $driver = null)
    {
        $this->cache = $driver;
        return $this;
    }
    
    /**
     * Sets the cache strategy to use.
     * 
     * @param Model_Cache_StrategyInterface $strategy The strategy to handle the cache.
     * 
     * @return Model_Driver
     */
    public function setCacheStrategy(Model_Cache_StrategyInterface $strategy = null)
    {
        $this->strategy = $strategy;
        return $this;
    }
    
    /**
     * Generates a cache key based on the parameters passed.
     * 
     * @param string $model  The model classname.
     * @param string $method The method name.
     * @param array  $params The parameters passed to the method.
     * 
     * @return string
     */
    protected function generateCacheKey($model, $method, array $params = array())
    {
        return md5($model . '->' . $method . serialize($params));
    }
    
    private function _fromCache($key)
    {
        if ($this->cache) {
            if ($value = $this->cache->get($key)) {
                return $this->_decode($value);
            }
        }
        return null;
    }
    
    private function _toCache($key, $value)
    {
        if ($this->cache) {
            $value = $this->_encode($value);
            $this->cache->set($key, $value);
        }
        return $this;
    }
    
    private function _encode($value)
    {
        if ($this->strategy instanceof Model_Cache_StrategyInterface) {
            $value = $this->strategy->encode($value);
        }
        return $value;
    }
    
    private function _decode($value)
    {
        if ($this->strategy instanceof Model_Cache_StrategyInterface) {
            $value = $this->strategy->decode($value);
        }
        return $value;
    }
}